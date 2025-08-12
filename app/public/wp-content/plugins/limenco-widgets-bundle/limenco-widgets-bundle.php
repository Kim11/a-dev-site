<?php
/*
Plugin Name: Limenco Widgets Bundle
Description: A pack of custom widgets for SiteOrigin.
Version: 1.0.1
Author: Limenco Design
Author URI: https://limencodesign.co.za
Plugin URI: https://limencodesign.co.za
*/

if (!defined('ABSPATH')) exit;

add_filter( 'siteorigin_widgets_widget_folders', function( $folders ) {
    $folders[] = plugin_dir_path( __FILE__ ) . 'widgets/';
    return $folders;
});


