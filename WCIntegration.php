<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WCIntegration{

    function __construct()
    {
        add_action( 'wp_ajax_nopriv_saveDataToSession', array($this, 'saveDataToSession'));
        add_action('wp_ajax_saveDataToSession', array($this, 'saveDataToSession'));
        add_action( 'wp_ajax_nopriv_addItemToCart', array( $this, 'addItemToCart') );
        add_action( 'wp_ajax_addItemToCart', array( $this, 'addItemToCart') );
        add_action( 'wp_ajax_nopriv_addLikesItemToCart', array( $this, 'addLikesItemToCart') );
        add_action( 'wp_ajax_addLikesItemToCart', array( $this, 'addLikesItemToCart') );
        add_action( 'wp_ajax_nopriv_getCartItems', array( $this, 'getCartItems') );
        add_action( 'wp_ajax_getCartItems', array( $this, 'getCartItems') );
        add_action( 'wp_ajax_nopriv_getMetaData', array( $this, 'getMetaData') );
        add_action( 'wp_ajax_getMetaData', array( $this, 'getMetaData') );
        add_action( 'wp_ajax_nopriv_getFromSession', array( $this, 'getFromSession') );
        add_action( 'wp_ajax_getFromSession', array( $this, 'getFromSession') );
        add_action( 'wp_ajax_getOrderData', array( $this, 'getOrderData') );
        add_action( 'wp_ajax_nopriv_getOrderData', array( $this, 'getOrderData') );
        add_action( 'wp_ajax_nopriv_getUserInstagramData', array( $this, 'getUserInstagramData') );
        add_action( 'wp_ajax_getUserInstagramData', array( $this, 'getUserInstagramData') );
        add_action('woocommerce_new_order_item', array($this, 'add_custom_data_order_item'));
        add_filter( 'woocommerce_add_to_cart_redirect', array($this,'custom_add_to_cart_redirect' ));

    }

    function redirect($url, $statusCode = 303)
    {
        header('Location: ' . $url, true, $statusCode);
    }

    /**
     * @param $product_id
     * @return bool
     * check if product is in woocommerce cart
     */
    function woo_in_cart($product_id) {
        global $woocommerce;

        foreach($woocommerce->cart->get_cart() as $key => $val ) {
            $_product = $val['data'];

            if($product_id == $_product->id ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Add item to cart
     * Arguments passed from Ajax
     *
     * params [$product_id, $username, $type]
     * $product_id || int
     * $username || string
     * $type || string
     */
    function addItemToCart(){
        $product_id = $_POST['product_id'];
        $username = $_POST['username'];
        $follower_quantity = $_POST['follower_quantity'];
        $order_type = $_POST['order_type'];

        $cart_item_data = [
            "product_id"=>$product_id,
            "username"=>$username
        ];

        if(!$this->woo_in_cart($product_id)){
            WC()->cart->add_to_cart(
                $product_id = $product_id,
                $quantity = 1,
                $variation_id = 0,
                [],
                $cart_item_data);

            //set meta data in session
            WC()->session->set(
                'IP_session',
                array(
                    'username' => $username,
                    'order_type' => $order_type,
                    'follower_quantity' => $follower_quantity
                ));
            ;
        }

        $url = WC()->cart->get_checkout_url();
        echo  json_encode(["checkout_url"=> $url]);
        die();
    }

    function addLikesItemToCart(){
            $product_Id = $_POST['product_id'];
            $username = $_POST['username'];
            $order_data = $_POST['order_data'];
            $order_type = $_POST['order_type'];

            $cart_item_data = [
                "product_id"=>$product_Id,
                "username"=>$username,
                "order_data"=> $order_data
            ];

            if(!$this->woo_in_cart($product_Id)){
                WC()->cart->add_to_cart(
                    $product_id = $product_Id,
                    $quantity = 1,
                    $variation_id = 0,
                    [],
                    $cart_item_data);

                //set meta data in session
                WC()->session->set(
                    'IP_session',
                    array(
                        'username' => $username,
                        'order_type' => $order_type,
                        'order_data' => $order_data
                    ));
            }
            echo  json_encode($cart_item_data);
            die();
        }

    function getMetaData(){
        $order = new WC_Order(4011);
        $meta =$order->get_meta_data();
        echo json_encode($meta);
        die();
    }

    function getFromSession(){
        $cart_count = WC()->session->get('IP_session');
        echo json_encode($cart_count);
        die();
    }

    function getOrderData(){
        $meta = wc_get_order_item_meta(4);
        echo json_encode($meta);
        die();
    }

    function getUserInstagramData(){
        $_response = wp_remote_get("https://www.instagram.com/".$_POST['username']."?__a=1");
        print_r($_response["body"]);
        die();
    }
    
    function add_custom_data_order_item($item_id, $values, $cart_item_key) {
        error_log("add custom data order item hit");
        $session_var  = 'IP_session';
        $session_data = WC()->session->get($session_var);
        if(!empty($session_data))
            wc_add_order_item_meta($item_id, $session_var, $session_data);
        else
            error_log("no session data", 0);
    }

    function my_custom_add_to_cart_redirect( $url ) {
        $url = WC()->cart->get_checkout_url();
        return $url;
    }


}

