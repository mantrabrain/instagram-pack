<?php
/**
 * Instagram_Pack Miscellaneous Settings
 *
 * @package Instagram_Pack/Admin
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

if (class_exists('Instagram_Pack_Settings_General', false)) {
    return new Instagram_Pack_Settings_General();
}

/**
 * Instagram_Pack_Settings_General.
 */
class Instagram_Pack_Settings_General extends Instagram_Pack_Admin_Settings_Base
{

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->id = 'general';
        $this->label = __('General', 'instagram-pack');

        parent::__construct();
    }

    /**
     * Get sections.
     *
     * @return array
     */
    public function get_sections()
    {
        $sections = array(
            '' => __('General', 'instagram-pack'),
            'pages' => __('Pages', 'instagram-pack'),
        );

        return apply_filters('instagram_pack_get_sections_' . $this->id, $sections);
    }

    /**
     * Output the settings.
     */
    public function output()
    {
        global $current_section;

        $settings = $this->get_settings($current_section);

        Instagram_Pack_Admin_Settings::output_fields($settings);
    }

    /**
     * Save settings.
     */
    public function save()
    {
        global $current_section;

        $settings = $this->get_settings($current_section);
        Instagram_Pack_Admin_Settings::save_fields($settings);

        if ($current_section) {
            do_action('instagram_pack_update_options_' . $this->id . '_' . $current_section);
        }
    }

    /**
     * Get settings array.
     *
     * @param string $current_section Current section name.
     * @return array
     */
    public function get_settings($current_section = '')
    {
        if ('pages' === $current_section) {
            $settings = apply_filters(
                'instagram_pack_settings_general_pages',
                array(/*array(
                        'title' => __('Page Settings', 'instagram-pack'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'instagram_pack_pages_options',
                    ),

                    array(
                        'title' => __('Checkout Page', 'instagram-pack'),
                        'desc' => __('Checkout page for tour booking', 'instagram-pack'),
                        'id' => 'instagram_pack_checkout_page',
                        'type' => 'link',
                        'href' => 'https://google.com',
                        'default' => 'Instagram',
                    ),
                    array(
                        'title' => __('Thank you page', 'instagram-pack'),
                        'desc' => __('Thank you page after tour booking', 'instagram-pack'),
                        'id' => 'instagram_pack_thankyou_page',
                        'type' => 'single_select_page',
                    ),
                    array(
                        'title' => __('Terms and conditions page', 'instagram-pack'),
                        'desc' => __('Page for your terms and condition.', 'instagram-pack'),
                        'id' => 'instagram_pack_termsandconditions_page',
                        'type' => 'single_select_page',
                    ),

                    array(
                        'type' => 'sectionend',
                        'id' => 'instagram_pack_pages_options',
                    ),*/

                )
            );

        } else {

            $website_token = sha1('website_token' . site_url());

            $token_url = 'https://api.instagram.com/oauth/authorize/?client_id=d5277d635c664bd4b0b0d5d7ee27d10d&scope=basic+public_content&redirect_uri=https://api.mantrabrain.com/plugins/instagram-token.php?return_uri=' . admin_url('admin.php?page=instagram-pack') . '&response_type=code&state=' . admin_url('admin.php?page=instagram-pack') . '&hl=en&website_token=' . $website_token;

            $token_url = 'https://instagram.pixelunion.net/';

            $settings = apply_filters(
                'instagram_pack_settings_general_general',
                array(
                    array(
                        'title' => __('General Settings', 'instagram-pack'),
                        'type' => 'title',
                        'desc' => '',
                        'id' => 'instagram_pack_general_options',
                    ),
                    array(
                        'title' => '',
                        'desc' => __('Click here to get instagram token from here and paste it to Access Token Box and save it and add this [instagram_pack_feed] shortcode into your page,posts or widgets.', 'instagram-pack'),
                        'id' => 'instagram_pack_currency',
                        'default' => __('Click here to get instagram access token', 'instagram-pack'),
                        'type' => 'link',
                        'href' => $token_url,
                        'target' => '_blank'
                    ),
                    array(
                        'title' => __('Access Token', 'instagram-pack'),
                        'desc' => __('Instagram Access Token.', 'instagram-pack'),
                        'id' => 'access_token',
                        'type' => 'text',
                    ),
                    array(
                        'title' => __('Instagram Gallery Grid', 'instagram-pack'),
                        'desc' => __('Select grid for instagram feed', 'instagram-pack'),
                        'id' => 'instagram_pack_feed_grid',
                        'default' => 3,
                        'type' => 'select',
                        'options' => array(
                            '1' => 1,
                            '2' => 2,
                            '3' => 3,
                            '4' => 4,
                        )
                    ),
                    array(
                        'title' => __('Per Page Posts', 'instagram-pack'),
                        'desc' => __('Per Page Posts', 'instagram-pack'),
                        'id' => 'per_page_posts',
                        'type' => 'number',
                        'default' => 10,
                    ),
                    array(
                        'title' => __('Hide post like counter', 'instagram-pack'),
                        'desc' => __('Click here to hide post like counter for individual post', 'instagram-pack'),
                        'id' => 'hide_post_like_count',
                        'type' => 'checkbox',
                    ), array(
                    'title' => __('Hide post comment counter', 'instagram-pack'),
                    'desc' => __('Click here to hide post comment counter for individual post', 'instagram-pack'),
                    'id' => 'hide_comment_count',
                    'type' => 'checkbox',
                ), array(
                    'title' => __('Load More Text', 'instagram-pack'),
                    'desc' => __('Load more text button label', 'instagram-pack'),
                    'id' => 'load_more_text',
                    'type' => 'text',
                    'default' => __('Load more..', 'instagram-pack'),
                ), array(
                    'title' => __('Load More Loading Text', 'instagram-pack'),
                    'desc' => __('Load more loading text button label', 'instagram-pack'),
                    'id' => 'load_more_loading_text',
                    'type' => 'text',
                    'default' => __('Loading...', 'instagram-pack'),
                ), array(
                    'title' => __('Follow text', 'instagram-pack'),
                    'desc' => __('Follow text button label', 'instagram-pack'),
                    'id' => 'follow_text',
                    'type' => 'text',
                    'default' => __('Follow', 'instagram-pack'),
                ), array(
                    'type' => 'sectionend',
                    'id' => 'instagram_pack_general_options',
                ),

                )

            );
        }

        return apply_filters('instagram_pack_get_settings_' . $this->id, $settings, $current_section);
    }
}

return new Instagram_Pack_Settings_General();
