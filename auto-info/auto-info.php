<?php
/**
 * Plugin Name: Auto Info
 * Description: A plugin to collect and display vehicle data via a shortcode.
 * Version: 1.0
 * Author: <a href="https://www.linkedin.com/in/burhan-janjua/" target="_blank">Burhan Janjua</a>
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define constants
define('AUTO_INFO_VERSION', '1.0');
define('AUTO_INFO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('AUTO_INFO_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once AUTO_INFO_PLUGIN_DIR . 'includes/shortcode.php';
require_once AUTO_INFO_PLUGIN_DIR . 'includes/ajax-handlers.php';
require_once AUTO_INFO_PLUGIN_DIR . 'includes/settings.php';

// Enqueue styles and scripts
function auto_info_enqueue_assets() {
    wp_enqueue_style('auto-info-styles', AUTO_INFO_PLUGIN_URL . 'assets/styles.css', [], AUTO_INFO_VERSION);
    wp_enqueue_script('auto-info-scripts', AUTO_INFO_PLUGIN_URL . 'assets/scripts.js', ['jquery'], AUTO_INFO_VERSION, true);

    // Localize script for AJAX
    wp_localize_script('auto-info-scripts', 'autoInfoAjax', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'auto_info_enqueue_assets');
