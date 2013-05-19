# Auto Insert Title #

**Contributors:** Ralf Albert, Sergej Mueller

## Short Description ##
With this plugin it is possible to automatically fill in the title attribute of a link in the WordPress editor.

Auto Insert Title needs PHP v5.3+

## Description ##
With this plugin it is possible to automatically fill in the title attribute of a link in the WordPress editor.  
If tzhe user focus on the title field in the link insert popup, the plugin tries to fetch the url from the url field and requesting the url. If the requested page delivers a title attribute, this title will be copied into the title field.

## Installation ##
1. Search for the plugin name in your admin user interfaces plugin page. Then install it.

If you want to install the plugin manually:

1. Download "Auto Insert Title" from the GitHub repository
2. Unpack the archive.
3. Upload the unpacked archive folder to your plugins folder.
4. Activate the plugin.

## Changelog ##

### 0.2 ###

* fixing issue with url handling (replacing buggy esc_url_raw())
* fixing issue with using internal links

### 0.1 ###
* First public version on GitHub
