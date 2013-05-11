<?php
/**
 * WordPress-Plugin Automatically fill in the title if a text is linked in a post/page
 *
 * PHP version 5.3
 *
 * @category   PHP
 * @package    WordPress
 * @subpackage Auto Title To Link
 * @author     Ralf Albert <me@neun12.de>
 * @license    GPLv3 http://www.gnu.org/licenses/gpl-3.0.txt
 * @version    0.1
 * @link       http://wordpress.com
 */

/**
 * Plugin Name:	Autoinsert Title To Link
 * Plugin URI:	http://yoda.neun12.de
 * Description:	Automatically insert the title if a text is linked in the WordPress editor
 * Version: 	0.1
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
	// load minified version if SCRIPT_DEBUG is true
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
}

/**
 * Callback for the ajax action
 * @wp-hook: wp_ajax_auto_link_title
 *
 */
function ajaxcallback_for_auto_title_to_link() {
	// create function for sending the results
	$send_result = function( $title = '' ) {
		header( 'Content-type: application/json' );
		die( json_encode( array( 'title' => $title ) ) );
	};

	// check nonce
	$nonce = filter_input( INPUT_POST, 'nonce' );
	if ( ! wp_verify_nonce( $nonce, 'internal-linking' ) )
		$send_result( 'Invalid nonce: '.var_export($_POST,1) );

	// get url and check if the url could be valid
	$url = filter_input( INPUT_POST, 'url', FILTER_SANITIZE_URL );
	if ( empty( $url ) || false == parse_url( $url ) )
		$send_result();

	// url is passed, get the page content and extract the content of <title> tag
	$args = array(
			'sslverify' => false, // we trust every webpage
	);

	$webpage = wp_remote_get( $url, $args );

	if ( ! is_wp_error( $webpage ) ) {
		if ( isset( $webpage['response']['code'] ) && 200 === (int) $webpage['response']['code'] && ! empty( $webpage['body'] ) ) {
			preg_match( "/\<title\>(.*)\<\/title\>/", $webpage['body'], $title );
			if ( isset( $title[1] ) )
				$send_result( $title[1] );
		}
	}

	// no title found
	$send_result('');

}