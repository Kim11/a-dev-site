<?php
/**
 * Plugin Name: Lucide Icon Widget
 * Plugin URI: https://limencodesign.co.za/
 * Description: A widget to display Lucide icons in SiteOrigin
 * Version: 1.0.0
 * Author: Kim Coetzee, Limenco Design
 * Author URI: https://limencodesign.co.za/
 * Text Domain: lucide-icon-widget
 * Domain Path: /languages
 *
 * @package Lucide_Icon_Widget
 * @developer Kim Coetzee, Limenco Design (limencodesign.co.za)
 */

if (!defined('ABSPATH')) exit;

// Register the widget with SiteOrigin
add_action('widgets_init', function() {
    if (class_exists('SiteOrigin_Widget')) {
        include_once __DIR__ . '/lucide-icon-widget-class.php';
        register_widget('Lucide_Icon_Widget');
    }
});
