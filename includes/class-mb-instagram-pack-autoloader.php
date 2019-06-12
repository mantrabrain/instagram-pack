<?php
/**
 * MB_Instagram_Pack Autoloader.
 *
 * @package MB_Instagram_Pack/Classes
 * @version 1.0.0
 */

defined('ABSPATH') || exit;

/**
 * Autoloader class.
 */
class MB_Instagram_Pack_Autoloader
{

    /**
     * Path to the includes directory.
     *
     * @var string
     */
    private $include_path = '';

    /**
     * The Constructor.
     */
    public function __construct()
    {
        if (function_exists('__autoload')) {
            spl_autoload_register('__autoload');
        }

        spl_autoload_register(array($this, 'autoload'));

        $this->include_path = untrailingslashit(plugin_dir_path(MB_INSTAGRAM_PACK_FILE)) . '/includes/';
    }

    /**
     * Take a class name and turn it into a file name.
     *
     * @param  string $class Class name.
     * @return string
     */
    private function get_file_name_from_class($class)
    {
        return 'class-' . str_replace('_', '-', $class) . '.php';
    }

    /**
     * Include a class file.
     *
     * @param  string $path File path.
     * @return bool Successful or not.
     */
    private function load_file($path)
    {
        if ($path && is_readable($path)) {
            include_once $path;
            return true;
        }
        return false;
    }

    /**
     * Auto-load MB_Instagram_Pack classes on demand to reduce memory consumption.
     *
     * @param string $class Class name.
     */
    public function autoload($class)
    {

        $class = strtolower($class);


        if (0 !== strpos($class, 'mb_instagram_pack_')) {
            return;
        }

        $file = $this->get_file_name_from_class($class);

        $path = '';

        if (0 === strpos($class, 'mb_instagram_pack_shortcode')) {
            $path = $this->include_path . 'shortcodes/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_metabox')) {
            $path = $this->include_path . 'meta-boxes/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_taxonomy')) {
            $path = $this->include_path . 'taxonomy/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_custom_post_type')) {
            $path = $this->include_path . 'custom-post-type/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_admin')) {
            $path = $this->include_path . 'admin/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_customizer_control')) {
            $path = $this->include_path . 'customizer/control/';
        } elseif (0 === strpos($class, 'mb_instagram_pack_helper')) {
            $path = $this->include_path . 'helper/';
        }

        if (empty($path) || !$this->load_file($path . $file)) {
            $this->load_file($this->include_path . $file);
        }
    }
}

new MB_Instagram_Pack_Autoloader();
