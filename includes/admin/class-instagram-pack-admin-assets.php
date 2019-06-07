<?php
if (!class_exists('Instagram_Pack_Admin_Assets')) {
    class Instagram_Pack_Admin_Assets
    {
        function __construct()
        {
            add_action('admin_enqueue_scripts', array($this, 'load_admin_scripts'));

        }

        public function load_admin_scripts($hook)
        {

            // Register Only Script
            wp_register_script('instagram-pack-select2js', INSTAGRAM_PACK_PLUGIN_URI . '/assets/lib/select2/js/select2.min.js', false, INSTAGRAM_PACK_VERSION);

            // Register Only Styles
            wp_register_style('instagram-pack-select2css', INSTAGRAM_PACK_PLUGIN_URI . '/assets/lib/select2/css/select2.min.css', false, INSTAGRAM_PACK_VERSION);

            // Other Register and Enqueue
            wp_register_style('instagram-pack-admin-style', INSTAGRAM_PACK_PLUGIN_URI . '/assets/admin/css/admin-style.css', array('instagram-pack-select2css'), INSTAGRAM_PACK_VERSION);
            wp_enqueue_style('instagram-pack-admin-style');


            wp_register_script('instagram-pack-admin-script', INSTAGRAM_PACK_PLUGIN_URI . '/assets/admin/js/admin-script.js', array('instagram-pack-select2js'), INSTAGRAM_PACK_VERSION);
            wp_enqueue_script('instagram-pack-admin-script');

        }

    }

}
return new Instagram_Pack_Admin_Assets();