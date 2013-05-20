=== Plugin Name ===
Contributors: Ralf Albert, Sergej Mueller
Donate link: https://github.com/RalfAlbert/auto-title-to-link
Tags: usability, admin, link, editor
Requires at least: 3.5
Tested up to: 3.6
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

With this plugin it is possible to automatically fill in the title attribute of a link in WordPress editor.

This plugin requires PHP5.3+

== Description ==
With this plugin it is possible to automatically fill in the title attribute of a link in the WordPress editor.  
If the user focus on the title field in the "insert link" popup, the plugin tries to fetch the url from the url field
and requesting the url. If the requested url delivers a title attribute, this title will be copied into the title field.  
If an internal link is selected, the linktitle inserted by WordPress is used. It can be automatically overwritten by
focusing the title field.

This plugin requires PHP5.3+

== Installation ==
1. Search for the plugin name in your admin user interfaces plugin page. Then install it.

If you want to install the plugin manually:

1. Download "Auto Insert Title To Link" from the GitHub or WordPress repository
2. Unpack the archive. If you downloaded the plugin from the GitHub repository, remove the "-master" from the foldername
3. Upload the unpacked archive folder to your plugins folder.
4. Activate the plugin.

== Screenshots ==
1. Auto Insert Title To Link in action

== Changelog ==

= 1.0 =
* Final version for WordPress repository

= 0.2 =
* fixing issue with url handling (replacing buggy esc_url_raw())
* fixing issue with using internal links

= 0.1 =
* First public version on GitHub
