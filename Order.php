<?php

/**
 * Created by PhpStorm.
 * User: osho
 * Date: 6/12/17
 * Time: 11:07 PM
 */
class Order
{
    function __construct()
    {

        add_action( 'wp_enqueue_scripts', array($this, 'insta_promo_wp_enqueue_styles'));
        add_shortcode("insta_followers", array($this, "orderFollowers"));
        add_shortcode("insta_likes", array($this, "orderLikes"));
        add_action('wp_ajax_nopriv_addFollowerOrderToCart', array($this, 'addFollowerOrderToCart'));
        add_action( 'wp_ajax_addFollowerOrderToCart', array($this, 'addFollowerOrderToCart'));
        add_action('wp_ajax_nopriv_placeOrder', array($this, 'placeOrder'));
        add_action( 'wp_ajax_placeOrder', array($this, 'placeOrder'));
        $wcIntegration = new WCIntegration();
        $api = new Api();
    }

    /**
     * @param $attr || attributes from shortcode
     * @param null $content || content from shortcode
     * @return html code
     */
    function orderFollowers($attr, $content=null)
    {
        ob_start();
        extract($attr);
        $_product = wc_get_product( $attr['product_id'] );
        $_product_price = $_product->get_price();
        include plugin_dir_path(__FILE__).'templates/FollowerTemplate.php';
        return ob_get_clean();
    } 
    
    /**
     * @param $attr || attributes from shortcode
     * @param null $content || content from shortcode
     * @return html code
     */
    function orderLikes($attr, $content=null)
    {
        ob_start();
        extract($attr);
        $_product = wc_get_product( $attr['product_id'] );
        $_product_price = $_product->get_price();
        include plugin_dir_path(__FILE__).'templates/LikesTemplate.php';
        return ob_get_clean();
    }

    /**
     * Localize jquery and js scripts
     */
    function ajax_scripts(){
        wp_register_script( 'insta_promo_ajax',  plugins_url('instapromo/js/insta_function.js') );
        wp_enqueue_script( 'insta_promo_ajax');
        wp_localize_script( 'insta_promo_ajax', 'InstaAjax', array(
            // URL to wp-admin/admin-ajax.php to process the request
            'ajaxurl' => admin_url( 'admin-ajax.php' )
        ));
    }

    /**
     * enqueue css styles and jquery
     */
    function insta_promo_wp_enqueue_styles() {
        wp_register_style( 'instapromo-UI', plugins_url('instapromo/css/instapromo-UI.css'));
        wp_enqueue_style( 'instapromo-UI' );
        wp_enqueue_script('jquery');
        $this->ajax_scripts();
    }

    /**
     * Stubbing Place Order
     */
    function placeOrder(){
        $address = array(
            'first_name' => "Klinsmann",
            'last_name'  => 'Osho',
            'company'    => '',
            'email'      => "oshoklinsmann@gmail.com",
            'phone'      => "07069291995",
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => ''
        );

        $order = wc_create_order();

        // add products from cart to order
        $items = WC()->cart->get_cart();
        foreach($items as $item => $values) {
            $product_id = $values['product_id'];
            $product = wc_get_product($product_id);
            $var_id = $values['variation_id'];
            $var_slug = $values['variation']['attribute_pa_weight'];
            $quantity = (int)$values['quantity'];
            $variationsArray = array();
            $variationsArray['variation'] = array(
                'pa_weight' => $var_slug
            );
            $var_product = new WC_Product_Variation($var_id);
            $order->add_product($var_product, $quantity, $variationsArray);
        }

        $order->set_address( $address, 'billing' );
        $order->set_address( $address, 'shipping' );

        $order->calculate_totals();
        $order->update_status( 'processing' );
        do_action('woocommerce_new_order_item', 4, $items, 2, true);
        WC()->cart->empty_cart();
    }

}