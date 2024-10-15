<?php
/**
 * Plugin Name: Neotel Integration for Contact Form 7
 * Description: Integrates Contact Form 7 with Neotel API
 * Version: 1.0
 * Author: Your Name
 * GitHub Plugin URI: https://github.com/yourusername/neotel-integration
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Define plugin constants
define('NEOTEL_INTEGRATION_PATH', plugin_dir_path(__FILE__));
define('NEOTEL_INTEGRATION_URL', plugin_dir_url(__FILE__));

// Include necessary files
require_once NEOTEL_INTEGRATION_PATH . 'includes/admin-tab.php';
require_once NEOTEL_INTEGRATION_PATH . 'includes/form-handling.php';
require_once NEOTEL_INTEGRATION_PATH . 'includes/api-integration.php';

// Include Plugin Update Checker
require_once NEOTEL_INTEGRATION_PATH . 'plugin-update-checker/plugin-update-checker.php';
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

// Set up the update checker
$myUpdateChecker = PucFactory::buildUpdateChecker(
    'https://github.com/yourusername/neotel-integration/',
    __FILE__,
    'neotel-integration'
);

// Set the branch that contains the stable release
$myUpdateChecker->setBranch('main');

// Enqueue admin scripts and styles
function neotel_integration_enqueue_admin_scripts($hook) {
    if ('toplevel_page_wpcf7' !== $hook) {
        return;
    }
    wp_enqueue_script('neotel-integration-admin', NEOTEL_INTEGRATION_URL . 'assets/js/admin.js', array('jquery'), '1.0', true);
    wp_enqueue_style('neotel-integration-admin', NEOTEL_INTEGRATION_URL . 'assets/css/admin.css', array(), '1.0');
}
add_action('admin_enqueue_scripts', 'neotel_integration_enqueue_admin_scripts');