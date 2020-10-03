<?php
/*
Plugin Name: WPSS Cookies Consent
Plugin URI: https://angelorocha.com.br
Description: A simple way to add a cookie consent message in your WordPress
Version: 1.0.0
Author: Angelo Rocha
Author URI: https://angelorocha.com.br
Domain Path: /lang
Text Domain: wpss
*/

defined('ABSPATH') or die('Oooooh Boy...');

define("_WPSS_PLUGIN_URL", plugin_dir_url(__FILE__));
define("_WPSS_PLUGIN_DIR", plugin_dir_path(__FILE__));
define("_WPSS_PLUGIN_VERSION", 20201002);

if(!class_exists('WPSSCookiesAdmin')){
    if(is_admin()){
        require_once 'core/WPSSCookiesAdmin.php';
        new WPSSCookiesAdmin();
        register_activation_hook(__FILE__, array('WPSSCookiesAdmin', 'wpss_on_plugin_activate'));
        register_deactivation_hook(__FILE__, array('WPSSCookiesAdmin', 'wpss_on_plugin_deactivate'));
    }
}