<?php
/*
Plugin Name: WPSS Cookies Consent
Plugin URI: https://github.com/wpsuperstars/wpss_cookies
Description: A simple way to add a cookie consent message in your WordPress
Version: 1.0.0
Requires at least: 5.2
Requires PHP: 7.2
Author: Angelo Rocha
Author URI: https://angelorocha.com.br
License: GNU General Public License v3 or later
License URI: /LICENSE
Text Domain: wpss
Domain Path: /lang
*/

// Prevents errors if called directly
if(!function_exists('add_action')){
    echo "Don't call me directly, i don't exist...";
    exit;
}

// Plugin constants
define("_WPSS_PLUGIN_URL", plugin_dir_url(__FILE__));
define("_WPSS_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("_WPSS_PLUGIN_VERSION", 20201002);

// Admin Class
if(!class_exists('WPSSCookiesAdmin')){
    if(is_admin()){
        require_once 'core/WPSSCookiesAdmin.php';
        $wpss_cookie_admin = new WPSSCookiesAdmin();
        register_activation_hook(__FILE__, array('WPSSCookiesAdmin', 'wpss_on_plugin_activate'));
        register_deactivation_hook(__FILE__, array('WPSSCookiesAdmin', 'wpss_on_plugin_deactivate'));
    }
}

// Frontend Class
if(!class_exists('WPSScookies')){
    require_once 'core/WPSScookies.php';
    $wpss_cookie = new WPSScookies();
    add_action('wp_ajax_wpss_set_cookie_action', array('WPSScookies', 'wpss_set_plugin_cookie'));
    add_action('wp_ajax_nopriv_wpss_set_cookie_action', array('WPSScookies', 'wpss_set_plugin_cookie'));
}