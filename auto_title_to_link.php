<?php
/**
 * WordPress-Plugin Automatically insert the title if a link is created in the WordPress editor popup
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Auto Insert Title To Link
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    1.0.1
 * @link       http://wordpress.com
 */

/**
 * Plugin Name:	Auto Insert Title To Link
 * Plugin URI:	http://yoda.neun12.de
 * Description:	Automatically insert the title when a link is created in the WordPress editor popup
 * Version: 	1.0.1
 * Author: 		Ralf Albert
 * Author URI: 	http://yoda.neun12.de
 * Network:     true
 * License:		GPLv3
 */

/*
 This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/
namespace AutoTitleToLink;

( ! defined( 'ABSPATH' ) ) AND die( "Standing On The Shoulders Of Giants" );

/**
 * Initialize the plugin on 'admin_init'
 */
add_action( 'admin_init', __NAMESPACE__ . '\hooks_for_auto_title_to_link', 10, 0 );

/**
 * Adding the needed hooks&filters
 * @wp-hook: admin_init
 */
function hooks_for_auto_title_to_link() {
	add_action( 'load-post.php',           __NAMESPACE__ . '\enqueue_scripts_for_auto_title_to_link', 10, 0 );
	add_action( 'load-post-new.php',       __NAMESPACE__ . '\enqueue_scripts_for_auto_title_to_link', 10, 0 );
	add_action( 'wp_ajax_auto_link_title', __NAMESPACE__ . '\ajaxcallback_for_auto_title_to_link', 10, 0 );
}

/**
 * Registers and enqueues the javascript
 * Load a unminified debugging version if SCRIPT_DEBUG is not defined or not true.
 * @wp-hook: load-post.php
 * @wp-hook: load-post-new.php
 * @constant: SCRIPT_DEBUG
 */
function enqueue_scripts_for_auto_title_to_link() {

	// load minified version if SCRIPT_DEBUG is not defined or not true
	$min = ( defined( 'SCRIPT_DEBUG' ) && true == SCRIPT_DEBUG ) ? '' : '.min';
	wp_enqueue_script(
		'auto-insert-title',
		plugins_url(
			sprintf( 'js/auto_title_to_link%s.js', $min ),
			__FILE__
		),
		array( 'jquery' ),
		false,
		true
	);

	// set JS object with params
	wp_localize_script( 'auto-insert-title', 'AutoInsertTitle', array( 'ajaxloaderUrl' => plugins_url( 'js/ajax-loader.gif', __FILE__ ) ) );

}

/**
 * Callback for the ajax action
 * @wp-hook: wp_ajax_auto_link_title
 *
 */
function ajaxcallback_for_auto_title_to_link() {
	// create function for sending the results
	$send_result = function( $title = '' ){
		header( 'Content-type: application/json' );
		die( json_encode( array( 'title' => $title ) ) );
	};

	// check nonce
	$nonce = filter_input( INPUT_POST, 'nonce' );
	if ( ! wp_verify_nonce( $nonce, 'internal-linking' ) )
		$send_result( 'Invalid request!' ); // this should never happen

	// get url and check if the url could be valid
	$url = filter_input( INPUT_POST, 'url' );
	$url = esc_url( $url, array('http', 'https') );

	if ( empty( $url ) || false == parse_url( $url ) )
		$send_result();

	// url is passed, get the page content and extract the content of <title> tag
	$args = array(
			'sslverify' => false, // we trust every webpage
	);

	$webpage = wp_remote_get( $url, $args );

	if ( ! is_wp_error( $webpage ) ) {
		if ( wp_remote_retrieve_response_code($webpage) === 200 && $body = wp_remote_retrieve_body($webpage) ) {
			preg_match( "/\<title\>(.*)\<\/title\>/", $body, $title );
			if ( isset( $title[1] ) )
				$send_result( html_entity_decode( $title[1], ENT_HTML5, 'UTF-8' ) );
		}
	}

	// no title found
	$send_result();

}

/**
 * Modified copy of the original WordPress function esc_url()
 * The original function does not check if the array $url has the key 0 and throws an warning
 *
 * @param string $url The URL to be cleaned.
 * @param array $protocols Optional. An array of acceptable protocols.
 *		Defaults to 'http', 'https', 'ftp', 'ftps', 'mailto', 'news', 'irc', 'gopher', 'nntp', 'feed', 'telnet', 'mms', 'rtsp', 'svn' if not set.
 * @param string $_context Private. Use esc_url_raw() for database usage.
 * @return string The cleaned $url after the 'clean_url' filter is applied.
 */
function esc_url( $url, $protocols = null, $_context = 'dp' ) {
	$original_url = $url;

	if ( '' == $url )
		return $url;
	$url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
	$strip = array('%0d', '%0a', '%0D', '%0A');
	$url = _deep_replace($strip, $url);
	$url = str_replace(';//', '://', $url);
	/* If the URL doesn't appear to contain a scheme, we
	 * presume it needs http:// appended (unless a relative
	 		* link starting with /, # or ? or a php file).
	*/
	if ( isset( $url[0] ) ) {
		if ( strpos($url, ':') === false && ! in_array( $url[0], array( '/', '#', '?' ) ) && ! preg_match('/^[a-z0-9-]+?\.php/i', $url) )
			$url = 'http://' . $url;
	}

	if ( ! is_array( $protocols ) )
		$protocols = wp_allowed_protocols();
	if ( wp_kses_bad_protocol( $url, $protocols ) != $url )
		return '';

	return apply_filters('clean_url', $url, $original_url, $_context);
}
