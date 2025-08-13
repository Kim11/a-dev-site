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

// Register Limenco Add Ons top-level menu and all submenus
add_action('admin_menu', function() {
    // Top-level Limenco Add Ons menu with custom SVG icon
    add_menu_page(
        'Limenco Add Ons',
        'Limenco Add Ons',
        'manage_options',
        'limenco-widgets',
        function() { echo '<div class="wrap"><h1>Limenco Widgets Bundle</h1><p>Welcome to the Limenco Widgets Bundle admin panel.</p></div>'; },
        plugins_url('wp-menu-icon.svg', __FILE__),
        56
    );
    // Submenu for Button Grid
    add_submenu_page(
        'limenco-widgets',
        'Button Grid',
        'Button Grid',
        'manage_options',
        'limenco-button-grid',
        function() { echo '<div class="wrap"><h1>Button Grid Widget</h1><p>Settings and info for Button Grid widget.</p></div>'; }
    );
    // Submenu for Backgrounds
    add_submenu_page(
        'limenco-widgets',
        'Backgrounds',
        'Backgrounds',
        'manage_options',
        'limenco-backgrounds',
        function() { echo '<div class="wrap"><h1>Backgrounds Widget</h1><p>Settings and info for Backgrounds widget.</p></div>'; }
    );
    // Submenu for Subscribe (callback from subscribe widget file)
    add_submenu_page(
        'limenco-widgets',
        'Subscribe',
        'Subscribe',
        'manage_options',
        'limenco-subscribe',
        'limenco_subscribe_widget_settings_page'
    );
});

// Force admin menu SVG icon to 20x20px
add_action('admin_head', function() {
    echo '<style>
    #adminmenu .toplevel_page_limenco-widgets .wp-menu-image img {
        height: 20px !important;
        width: auto !important;
        max-width: 31px !important;
        display: inline-block;
    }
    </style>';
});

// Ensure Limenco Subscribe admin and logic is loaded
require_once __DIR__ . '/limenco-subscribe-widget.php';


