<?php
/**
 * WPSS Cookie Consent
 *
 * @package           wpss_cookies
 * @author            Angelo Rocha
 * @copyleft          2020
 * @license           GPL-3.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       WPSS Cookies Consent
 * Plugin URI:        https://github.com/wpsuperstars/wpss_cookies
 * Description:       A simple way to add a cookie consent message in your WordPress
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Angelo Rocha
 * Author URI:        https://angelorocha.com.br
 * Text Domain:       wpss
 * License:           GNU General Public License v3 or later
 * License URI:       /LICENSE
 */

final class WPSScookies{

    /**
     * Define default cookie life to a one year
     * @var int
     */
    private static $cookie_time = 31556926;

    /**
     * Define default cookie name
     * @var string
     */
    private static $cookie_name = "wpss_cookie_accepted";

    /**
     * Define default cookie value
     * @var string
     */
    private static $cookie_value = "accepted";

    public function __construct(){
        if(self::wpss_message_is_active() && !self::wpss_check_cookie_exists()):
            add_action('wp_enqueue_scripts', array($this, 'wpss_plugin_scripts'));
        endif;
        add_action('wp_footer', array($this, 'wpss_show_cookie_message'));
    }

    /**
     * Get plugin frontend
     */
    public function wpss_show_cookie_message(){
        if(self::wpss_message_is_active() && !self::wpss_check_cookie_exists()):
            self::wpss_plugin_frontend();
        endif;
    }

    /**
     * Render plugin in frontend
     */
    public function wpss_plugin_frontend(){
        $position     = "wpss_" . get_option('wpss_message_position');
        $theme        = get_option('wpss_message_style');
        $ajax_url     = admin_url('admin-ajax.php');
        $secure_nonce = wp_create_nonce('wpss_cookie_secure_request');
        echo "<div data-url='$ajax_url' data-security='$secure_nonce' class='wpss-cookie-message $theme $position'>";
        echo "<div class='wpss-container'>";
        echo "<div class='wpss-container-message'>";
        echo self::wpss_get_message_option();
        echo "</div>";
        echo "<div class='wpss-container-button'>";
        echo self::wpss_get_button_option();
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Get cookie message option
     * @return false|mixed|void
     */
    public function wpss_get_message_option(){
        return get_option('wpss_cookie_message');
    }

    /**
     * Get cookie accept button
     * @return string
     */
    public function wpss_get_button_option(){
        $button_text = get_option('wpss_button_text');
        return "<button type='button' class='wpss-button-accept'>$button_text</button>";
    }

    /**
     * Check if plugin message is true
     * @return bool
     */
    public function wpss_message_is_active(){
        if(!is_null(get_option('wpss_show_cookie_message'))):
            return (bool)get_option('wpss_show_cookie_message');
        endif;
        return false;
    }

    /**
     * Check if cookie exists
     * @return bool
     */
    public function wpss_check_cookie_exists(){
        if(isset($_COOKIE[self::$cookie_name])):
            return true;
        endif;
        return false;
    }

    /**
     * Set cookie
     */
    public function wpss_set_plugin_cookie(){
        check_ajax_referer('wpss_cookie_secure_request', 'security');
        setcookie(self::$cookie_name, self::$cookie_value, time() + self::$cookie_time, '/');
        exit;
    }

    /**
     * Get plugin scripts
     */
    public function wpss_plugin_scripts(){
        wp_enqueue_script('wpss-cookie', _WPSS_PLUGIN_URL . 'assets/js/wpss-cookie.js', array('jquery'), _WPSS_PLUGIN_VERSION, true);
        wp_enqueue_style('wpss-cookie', _WPSS_PLUGIN_URL . 'assets/css/wpss-cookie.css', '', _WPSS_PLUGIN_VERSION, 'all');
    }
}