<?php
if (!class_exists('Instagram_Pack_Assets')) {
    class Instagram_Pack_Assets
    {
        function __construct()
        {
            add_action('wp_enqueue_scripts', array($this, 'scripts'));

        }

        public function scripts($hook)
        {

            // Other Register and Enqueue
            wp_register_style('instagram-pack-font-awesome', INSTAGRAM_PACK_PLUGIN_URI . '/assets/lib/font-awesome/css/font-awesome.css', false, INSTAGRAM_PACK_VERSION);
            wp_enqueue_style('instagram-pack-font-awesome');

            // Other Register and Enqueue
            wp_register_style('instagram-pack-style', INSTAGRAM_PACK_PLUGIN_URI . '/assets/css/instagram-pack.css', false, INSTAGRAM_PACK_VERSION);
            wp_enqueue_style('instagram-pack-style');


            wp_register_script('instagram-pack-script', INSTAGRAM_PACK_PLUGIN_URI . '/assets/js/instagram-pack.js', array('jquery'), INSTAGRAM_PACK_VERSION);
            wp_enqueue_script('instagram-pack-script');

            $instagram_pack_params = array(

                'ajax_url' => admin_url('admin-ajax.php'),
                'load_more_profile_params' => array(
                    'load_more_profile_action' => 'instagram_pack_load_more_profile',
                    'load_more_profile_nonce' => wp_create_nonce('wp_instagram_pack_load_more_profile_nonce')
                )
            );

            wp_localize_script('instagram-pack-script', 'instagram_pack_params', $instagram_pack_params);
        }

    }

}
return new Instagram_Pack_Assets();