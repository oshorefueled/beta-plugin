<?php
/*
Plugin Name: Instapromo
Plugin URI:  http://uritochangelater.com
Description: Plugin to order for instagram promotion packages
Version:     1.0.0
Author:      Poetic_Code
Author URI:  http://fiverr.com/poetic_code
*/


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    include "WCIntegration.php";
    include "Api.php";
    include "Forms.php";
    include "Order.php";
    include "ApiService.php";
    $instap_forms = new Forms("/instagram_view.php");
    $insta_followers = new Order();

    function instapromo_options_page()
    {
        add_menu_page(
            'Instapromo',
            'Instapromo',
            'manage_options',
            'insta_p',
            'instapromo_page_html',
            plugin_dir_url(__FILE__) . 'images/instagram-logo.png',
            20
        );
    }
    add_action('admin_menu', 'instapromo_options_page');

    function instapromo_settings_page()
    {
        add_submenu_page(
            'insta_p',
            'Insta Settings',
            'Insta Settings',
            'manage_options',
            'insta_settings',
            'instapromo_settings_page_html'
        );
    }
    add_action('admin_menu', 'instapromo_settings_page');


    function instapromo_settings_page_html(){
            ?>
            <div>
                <?php screen_icon(); ?>
                <h2>My Plugin Page Title</h2>
                <form method="post" action="options.php">
                    <?php settings_fields( 'myplugin_options_group' ); ?>
                    <table>
                        <tr valign="top">
                            <th scope="row"><label for="myplugin_option_name">Instagram Access Key</label></th>
                            <td><input type="text" id="myplugin_option_name" name="myplugin_option_name" value="<?php echo get_option('myplugin_option_name'); ?>" /></td>
                        </tr>
                    </table>
                    <?php  submit_button(); ?>
                </form>
            </div>
            <?php
    }

    function instapromo_page_html(){
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1>Welcome to Instapromo</h1>
        </div>
        <?php
    }

}


