# Auto Insert Title To Link #

**Contributors:** Ralf Albert, Sergej Mueller

## Short Description ##
With this plugin it is possible to automatically fill in the title attribute of a link in the WordPress editor.

Auto Insert Title To Link needs PHP v5.3+

## Description ##
With this plugin it is possible to automatically fill in the title attribute of a link in the WordPress editor.  
If the user focus on the title field in the link insert popup, the plugin tries to fetch the url from the url field and requesting the url. If the requested page delivers a title attribute, this title will be copied into the title field.

## Installation ##
1. Search for the plugin name in your admin user interfaces plugin page. Then install it.

If you want to install the plugin manually:

1. Download "Auto Insert Title To Link" from the GitHub or WordPress repository
2. Unpack the archive. If you downloaded the plugin from a GitHub repository, remove "-master" from the foldername
3. Upload the unpacked archive folder to your plugins folder.
4. Activate the plugin.

## Changelog ##

### 1.0.2 ###
Fix bug with PHP <5.4

### 1.0.1 ###
* Decode html entities in titles

### 1.0 ###
* Final version for WordPress repository

### 0.2 ###

* fixing issue with url handling (replacing buggy esc_url_raw())
* fixing issue with using internal links

### 0.1 ###
* First public version on GitHub
