<?php
/**
 * Instagram_Pack setup
 *
 * @package Instagram_Pack
 * @since   1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Main Instagram_Pack Class.
 *
 * @class Instagram_Pack
 */
final class Instagram_Pack
{

    /**
     * Instagram_Pack version.
     *
     * @var string
     */
    public $version = INSTAGRAM_PACK_VERSION;

    /**
     * The single instance of the class.
     *
     * @var Instagram_Pack
     * @since 1.0.0
     */
    protected static $_instance = null;


    /**
     * Main Instagram_Pack Instance.
     *
     * Ensures only one instance of Instagram_Pack is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see mb_aec_addons()
     * @return Instagram_Pack - Main instance.
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cloning is forbidden.', 'instagram-pack'), '1.0.0');
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Unserializing instances of this class is forbidden.', 'instagram-pack'), '1.0.0');
    }

    /**
     * Auto-load in-accessible properties on demand.
     *
     * @param mixed $key Key name.
     * @return mixed
     */
    public function __get($key)
    {
        if (in_array($key, array(''), true)) {
            return $this->$key();
        }
    }

    /**
     * Instagram_Pack Constructor.
     */
    public function __construct()
    {
        $this->define_constants();
        $this->includes();
        $this->init_hooks();
        do_action('instagram_pack_loaded');
    }

    /**
     * Hook into actions and filters.
     *
     * @since 1.0.0
     */
    private function init_hooks()
    {

        register_activation_hook(INSTAGRAM_PACK_FILE, array('Instagram_Pack_Install', 'install'));

        add_action('init', array($this, 'init'), 0);
        add_action('init', array($this, 'global_option'), 0);
        add_action('init', array('Instagram_Pack_Shortcodes', 'init'));


    }

    /**
     * Define Instagram_Pack Constants.
     */
    private function define_constants()
    {

        $this->define('INSTAGRAM_PACK_ABSPATH', dirname(INSTAGRAM_PACK_FILE) . '/');
        $this->define('INSTAGRAM_PACK_BASENAME', plugin_basename(INSTAGRAM_PACK_FILE));
    }

    /**
     * Define constant if not already set.
     *
     * @param string $name Constant name.
     * @param string|bool $value Constant value.
     */
    private function define($name, $value)
    {
        if (!defined($name)) {
            define($name, $value);
        }
    }

    /**
     * What type of request is this?
     *
     * @param  string $type admin, ajax, cron or frontend.
     * @return bool
     */
    private function is_request($type)
    {
        switch ($type) {
            case 'admin':
                return is_admin();
            case 'ajax':
                return defined('DOING_AJAX');
            case 'cron':
                return defined('DOING_CRON');
            case 'frontend':
                return (!is_admin() || defined('DOING_AJAX')) && !defined('DOING_CRON') && !defined('REST_REQUEST');
        }
    }

    /**
     * Include required core files used in admin and on the frontend.
     */
    public function includes()
    {

        /**
         * Class autoloader.
         */
        include_once INSTAGRAM_PACK_ABSPATH . 'includes/class-instagram-pack-autoloader.php';
        include_once INSTAGRAM_PACK_ABSPATH . 'includes/functions.php';
        include_once INSTAGRAM_PACK_ABSPATH . 'includes/class-instagram-pack-shortcodes.php';
        include_once INSTAGRAM_PACK_ABSPATH . 'includes/class-instagram-pack-ajax.php';


        if ($this->is_request('admin')) {
            Instagram_Pack_Admin::instance();
        }

        if ($this->is_request('frontend')) {
            Instagram_Pack_Frontend::instance();
        }

    }

    public function global_option()
    {
        global $instagram_pack_global_options;

        $instagram_pack_global_options = instagram_pack_get_option('instagram_pack_options', array(), true);
    }

    /**
     * Init Instagram_Pack when WordPress Initialises.
     */
    public function init()
    {
        // Before init action.
        do_action('before_instagram_pack_init');


        // Set up localisation.
        $this->load_plugin_textdomain();


        // Init action.
        do_action('instagram_pack_init');
    }

    /**
     * Load Localisation files.
     *
     * Note: the first-loaded translation file overrides any following ones if the same translation is present.
     *
     * Locales found in:
     *      - WP_LANG_DIR/instagram-pack/instagram-pack-LOCALE.mo
     *      - WP_LANG_DIR/plugins/instagram-pack-LOCALE.mo
     */
    public function load_plugin_textdomain()
    {
        $locale = is_admin() && function_exists('get_user_locale') ? get_user_locale() : get_locale();
        $locale = apply_filters('plugin_locale', $locale, 'instagram-pack');
        unload_textdomain('instagram-pack');
        load_textdomain('instagram-pack', WP_LANG_DIR . '/instagram-pack/instagram-pack-' . $locale . '.mo');
        load_plugin_textdomain('instagram-pack', false, plugin_basename(dirname(INSTAGRAM_PACK_FILE)) . '/i18n/languages');
    }

    /**
     * Ensure theme and server variable compatibility and setup image sizes.
     */
    public function setup_environment()
    {

        $this->define('INSTAGRAM_PACK_TEMPLATE_PATH', $this->template_path());

    }

    /**
     * Get the plugin url.
     *
     * @return string
     */
    public function plugin_url()
    {
        return untrailingslashit(plugins_url('/', INSTAGRAM_PACK_FILE));
    }

    /**
     * Get the plugin path.
     *
     * @return string
     */
    public function plugin_path()
    {
        return untrailingslashit(plugin_dir_path(INSTAGRAM_PACK_FILE));
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function template_path()
    {
        return apply_filters('instagram_pack_template_path', 'instagram-pack/');
    }

    /**
     * Get the template path.
     *
     * @return string
     */
    public function plugin_template_path()
    {
        return apply_filters('instagram_pack_plugin_template_path', $this->plugin_path() . '/templates/');
    }

    /**
     * Get Ajax URL.
     *
     * @return string
     */
    public function ajax_url()
    {
        return admin_url('admin-ajax.php', 'relative');
    }


}
