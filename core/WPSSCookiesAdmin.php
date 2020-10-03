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

final class WPSSCookiesAdmin{

    public function __construct(){
        add_action('admin_menu', array($this, 'wpss_plugin_menu_page'));
        add_action('admin_init', array($this, 'wpss_register_plugin_settings'));
        add_action('wp_enqueue_scripts', array($this, 'wpss_plugin_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'wpss_plugin_admin_scripts'));
        add_action('init', array($this, 'wpss_load_plugin_textdomain'));
    }

    /**
     * Plugin admin menu page
     */
    public function wpss_plugin_menu_page(){
        add_menu_page(
            __('Cookies Consent', 'wpss'),
            __('Cookies Consent', 'wpss'),
            'administrator',
            'wpss-cookies-consent',
            array($this, 'wpss_plugin_menu_content'),
            _WPSS_PLUGIN_URL . "assets/images/plugin-ico.png",
            5
        );
    }

    /**
     * Plugin admin page frontend
     */
    public function wpss_plugin_menu_content(){
        $plugin_title    = __('Cookie Consent Option', 'wpss');
        $message_label   = __('Edit Message', 'wpss');
        $editor_settings = array('media_buttons' => false, 'quicktags' => false, 'textarea_rows' => get_option('default_post_edit_rows', 5));
        echo "<div class='wpss-container'>";
        echo "<div class='wpss-save-msg'>";
        settings_errors();
        echo "</div>";
        echo "<h1>$plugin_title</h1>";
        echo "<form method='post' action='options.php'>";
        settings_fields('wpss_cookie_options');
        do_settings_sections('wpss_cookie_options');
        self::wpss_show_message_field();
        self::wpss_message_position_field();
        self::wpss_message_style_field();
        self::wpss_message_button_field();
        echo "<label for='wpss_cookie_message'>$message_label</label>";
        wp_editor(get_option('wpss_cookie_message'), 'wpss_cookie_message', $editor_settings);

        submit_button(__('Update Settings', 'wpss'));

        echo "</form>";
        echo "</div>";
    }

    /**
     * Register plugin settings
     */
    public function wpss_register_plugin_settings(){
        register_setting('wpss_cookie_options', 'wpss_show_cookie_message', self::wpss_sanitize_fields());
        register_setting('wpss_cookie_options', 'wpss_message_position', self::wpss_sanitize_fields());
        register_setting('wpss_cookie_options', 'wpss_message_style', self::wpss_sanitize_fields());
        register_setting('wpss_cookie_options', 'wpss_button_text', self::wpss_sanitize_fields());
        register_setting('wpss_cookie_options', 'wpss_cookie_message', self::wpss_sanitize_fields(
            'string',
            'wp_kses_post'
        ));
    }

    /**
     * Sanitize plugin fields
     * @param string $type
     * @param string $sanitize_cb
     * @param null $default
     * @return array
     */
    public function wpss_sanitize_fields($type = 'string', $sanitize_cb = 'sanitize_text_field', $default = null){
        return array(
            'type'              => $type,
            'sanitize_callback' => $sanitize_cb,
            'default'           => $default,
        );
    }

    /**
     * Enable cookie message field
     */
    public function wpss_show_message_field(){
        $enable_label = __('Enable Cookie Consent', 'wpss');
        $op_on        = __('Yes', 'wpss');
        $op_off       = __('No', 'wpss');
        echo "<div class='wpss-input-group'>";
        echo "<h3>$enable_label</h3>";
        echo "<label for='wpss_show_cookie_message_yes'>";
        echo "<input type='radio' name='wpss_show_cookie_message' id='wpss_show_cookie_message_yes' value='1'" . self::wpss_radio_checked('1', 'wpss_show_cookie_message') . ">$op_on</label>";
        echo "<label for='wpss_show_cookie_message_no'>";
        echo "<input type='radio' name='wpss_show_cookie_message' id='wpss_show_cookie_message_no' value='0'" . self::wpss_radio_checked('0', 'wpss_show_cookie_message') . ">$op_off</label>";
        echo "</div>";
    }

    /**
     * Message position field
     */
    public function wpss_message_position_field(){
        $position_label = __('Message Position', 'wpss');
        $op_top         = __('Top', 'wpss');
        $op_bottom      = __('Bottom', 'wpss');
        echo "<div class='wpss-input-group'>";
        echo "<h3>$position_label</h3>";
        echo "<label for='wpss_message_position_top'>";
        echo "<input type='radio' name='wpss_message_position' id='wpss_message_position_top' value='top'" . self::wpss_radio_checked('top', 'wpss_message_position') . ">$op_top</label>";
        echo "<label for='wpss_message_position_bottom'>";
        echo "<input type='radio' name='wpss_message_position' id='wpss_message_position_bottom' value='bottom'" . self::wpss_radio_checked('bottom', 'wpss_message_position') . ">$op_bottom</label>";
        echo "</div>";
    }

    /**
     * Message style field
     */
    public function wpss_message_style_field(){
        $style_label = __('Message Style', 'wpss');
        $ocean_op    = _WPSS_PLUGIN_URL . "assets/images/ocean_op.png";
        $light_op    = _WPSS_PLUGIN_URL . "assets/images/light_op.png";
        $forest_op   = _WPSS_PLUGIN_URL . "assets/images/forest_op.png";
        echo "<div class='wpss-input-group'>";
        echo "<h3>$style_label</h3>";
        echo "<div class='wpss-radio-image-inline'>";
        echo "<label for='wpss_message_style_ocean'>";
        echo "<input type='radio' name='wpss_message_style' id='wpss_message_style_ocean' value='wpss_ocean'" . self::wpss_radio_checked('wpss_ocean', 'wpss_message_style') . "> Ocean<img src='$ocean_op'></label>";
        echo "<label for='wpss_message_style_light'>";
        echo "<input type='radio' name='wpss_message_style' id='wpss_message_style_light' value='wpss_light'" . self::wpss_radio_checked('wpss_light', 'wpss_message_style') . "> Light<img src='$light_op'></label>";
        echo "<label for='wpss_message_style_forest'>";
        echo "<input type='radio' name='wpss_message_style' id='wpss_message_style_forest' value='wpss_forest'" . self::wpss_radio_checked('wpss_forest', 'wpss_message_style') . "> Forest<img src='$forest_op'></label>";
        echo "</div>";
        echo "</div>";
    }

    /**
     * Button text field
     */
    public function wpss_message_button_field(){
        $button_label = __('Button Label', 'wpss');
        $button_text  = get_option('wpss_button_text');
        echo "<div class='wpss-input-group'>";
        echo "<label for='wpss_button_text'><h3>$button_label</h3></label>";
        echo "<input type='text' name='wpss_button_text' id='wpss_button_text' value='$button_text'>";
        echo "</div>";
    }

    /***
     * Check current input radio value
     * @param $val
     * @param $option
     * @return string
     */
    public function wpss_radio_checked($val, $option){
        $checked = '';
        if($val === get_option($option)):
            $checked = ' checked';
        endif;

        return $checked;
    }

    /**
     * Plugin activate hook
     */
    public function wpss_on_plugin_activate(){
        $accept  = __('Accept', 'wpss');
        $message = __('We use cookies to provide our services and for analytics and marketing. To find out more about our use of cookies, please see our Privacy Policy. By continuing to browse our website, you agree to our use of cookies.', 'wpss');
        add_option('wpss_show_cookie_message', '0');
        add_option('wpss_message_position', 'bottom');
        add_option('wpss_message_style', 'wpss_ocean');
        add_option('wpss_button_text', $accept);
        add_option('wpss_cookie_message', $message);
    }

    /**
     * Plugin deactivate hook
     */
    public function wpss_on_plugin_deactivate(){
        delete_option('wpss_show_cookie_message');
        delete_option('wpss_message_position');
        delete_option('wpss_message_style');
        delete_option('wpss_button_text');
        delete_option('wpss_cookie_message');
    }

    /**
     * Plugin internationalization
     */
    public function wpss_load_plugin_textdomain(){
        load_plugin_textdomain('wpss', false, _WPSS_PLUGIN_DIR . 'lang');
    }

    /**
     * Get plugin admin scripts
     */
    public function wpss_plugin_admin_scripts(){
        if($_GET['page'] === 'wpss-cookies-consent'):
            wp_enqueue_script('wpss-cookie-admin', _WPSS_PLUGIN_URL . 'assets/js/wpss-cookie-admin.js', array('jquery'), _WPSS_PLUGIN_VERSION, true);
            wp_enqueue_style('wpss-cookie-admin', _WPSS_PLUGIN_URL . 'assets/css/wpss-cookie-admin.css', '', _WPSS_PLUGIN_VERSION, 'all');
        endif;
    }
}