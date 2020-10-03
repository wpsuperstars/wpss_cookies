<?php
/**
 * @author              Angelo Rocha
 * @author              Angelo Rocha <contato@angelorocha.com.br>
 * @link                https://angelorocha.com.br
 * @copyleft            2020
 * @license             GNU GPL 3 (https://www.gnu.org/licenses/gpl-3.0.html)
 * @package WordPress
 * @subpackage wpss_cookies
 * @since 1.0.0
 */

final class WPSScookies{

    public function __construct(){
        add_action('wp_enqueue_scripts', array($this, 'wpss_plugin_scripts'));
        add_action('wp_footer', array($this, 'wpss_plugin_frontend'));
    }

    /**
     * Render plugin in frontend
     */
    public function wpss_plugin_frontend(){
    }

    /**
     * Get plugin scripts
     */
    public function wpss_plugin_scripts(){
        wp_enqueue_script('wpss-cookie', _WPSS_PLUGIN_URL . 'assets/js/wpss-cookie.js', array('jquery'), _WPSS_PLUGIN_VERSION, true);
        wp_enqueue_style('wpss-cookie', _WPSS_PLUGIN_URL . 'assets/css/wpss-cookie.css', '', _WPSS_PLUGIN_VERSION, 'all');
    }
}