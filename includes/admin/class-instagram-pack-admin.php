<?php
/**
 * Instagram_Pack admin setup
 *
 * @package Instagram_Pack
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Main Instagram_Pack_Admin Class.
 *
 * @class Instagram_Pack
 */
final class Instagram_Pack_Admin
{

    /**
     * The single instance of the class.
     *
     * @var Instagram_Pack_Admin
     * @since 1.0.0
     */
    protected static $_instance = null;


    /**
     * Main Instagram_Pack_Admin Instance.
     *
     * Ensures only one instance of Instagram_Pack_Admin is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @return Instagram_Pack_Admin - Main instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    /**
     * Instagram_Pack Constructor.
     */
    public function __construct()
    {
        $this->includes();
        $this->init_hooks();
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     */
    private function init_hooks()
    {

        add_action('admin_menu', array($this, 'admin_menu'));

        add_action('instagram_pack_update_options_general', array($this, 'save_instagram_options'));

        add_filter('plugin_action_links_' . INSTAGRAM_PACK_FILE, array($this, 'settings_link'), 10, 2);


    }

    function settings_link($links, $file)
    {
        $settings_link = '<a href="' . admin_url('admin.php?page=instagram-pack') . '">' . __('Settings', 'instagram-pack') . '</a>';

        array_unshift($links, $settings_link);

        return $links;
    }

    function admin_menu()
    {

        $cap = current_user_can('manage_instagram_pack_options') ? 'manage_instagram_pack_options' : 'manage_options';

        add_menu_page(
            __('Instagram Pack Setting Page', 'instagram-pack'),
            __('Instagram', 'instagram-pack'),
            $cap,
            'instagram-pack',
            array($this, 'settings_page_init')
        );
        $settings_page = add_submenu_page(
            'settings.php',
            __('Settings', 'instagram-pack'),
            __('Settings', 'instagram-pack'),
            $cap,
            'instagram-pack',
            array($this, 'settings')
        );

        add_action('load-' . $settings_page, array($this, 'settings_page_init'));
    }

    public function settings()
    {

        Instagram_Pack_Admin_Settings::output();


    }

    public function settings_page_init()
    {

        global $current_tab, $current_section;

        // Include settings pages.
        Instagram_Pack_Admin_Settings::get_settings_pages();

        // Get current tab/section.
        $current_tab = empty($_GET['tab']) ? 'general' : sanitize_title(wp_unslash($_GET['tab'])); // WPCS: input var okay, CSRF ok.
        $current_section = empty($_REQUEST['section']) ? '' : sanitize_title(wp_unslash($_REQUEST['section'])); // WPCS: input var okay, CSRF ok.

        // Save settings if data has been posted.
        if ('' !== $current_section && apply_filters("instagram_pack_save_settings_{$current_tab}_{$current_section}", !empty($_POST['save']))) { // WPCS: input var okay, CSRF ok.
            Instagram_Pack_Admin_Settings::save();
        } elseif ('' === $current_section && apply_filters("instagram_pack_save_settings_{$current_tab}", !empty($_POST['save']))) { // WPCS: input var okay, CSRF ok.
            Instagram_Pack_Admin_Settings::save();
        }

        // Add any posted messages.
        if (!empty($_GET['instagram_pack_error'])) { // WPCS: input var okay, CSRF ok.
            Instagram_Pack_Admin_Settings::add_error(wp_kses_post(wp_unslash($_GET['instagram_pack_error']))); // WPCS: input var okay, CSRF ok.
        }

        if (!empty($_GET['instagram_pack_message'])) { // WPCS: input var okay, CSRF ok.
            Instagram_Pack_Admin_Settings::add_message(wp_kses_post(wp_unslash($_GET['instagram_pack_message']))); // WPCS: input var okay, CSRF ok.
        }

        do_action('instagram_pack_settings_page_init');


    }

    public function save_instagram_options()
    {
        global $instagram_pack_global_options;

        $instagram_pack_options = instagram_pack_get_option('instagram_pack_options', array(), true);

        $is_valid_token = isset($instagram_pack_options['is_valid_token']) ? (boolean)$instagram_pack_options['is_valid_token'] : false;

        $return_uri = admin_url('admin.php?page=instagram-pack');

        $is_token_change = @$instagram_pack_options['access_token'] != @$instagram_pack_global_options['access_token'] ? true : false;

        /*$access_token = isset($_GET['access_token']) ? sanitize_text_field($_GET['access_token']) : '';

        $website_token_from_api = isset($_GET['website_token']) ? sanitize_text_field($_GET['website_token']) : '';

        $website_token = sha1('website_token' . $return_uri . $access_token);*/

        //if (!empty($access_token) && $website_token === $website_token_from_api) {

        if (!$is_valid_token || $is_token_change) {

            $access_token = isset($instagram_pack_options['access_token']) ? $instagram_pack_options['access_token'] : '';//'1574536848.1677ed0.3d87306f533542f090612e177032140a';

            $user_data = array();

            try {

                $result_body = Instagram_Pack_API::instance()->get_user_from_token($access_token);

                $user_data = isset($result_body['data']['id']) ? $result_body['data'] : array();

                if (count($user_data) > 0) {

                    $instagram_pack_options['is_valid_token'] = true;


                } else {

                    $instagram_pack_options['is_valid_token'] = false;

                }

            } catch (Exception $e) {

                $instagram_pack_options['is_valid_token'] = false;
            }
            $instagram_pack_options['user_data'] = $user_data;

            $instagram_pack_options['access_token'] = $access_token;

            update_option('instagram_pack_options', $instagram_pack_options);

            wp_safe_redirect($return_uri);
        }
     }

    /**
     * Include required core files used in admin.
     */
    public function includes()
    {

        include_once INSTAGRAM_PACK_ABSPATH . 'includes/admin/class-instagram-pack-admin-assets.php';

    }


}
