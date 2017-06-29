<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Api
{
    function __construct()
    {
        add_action( 'wp_ajax_getServices', array( $this, 'getServices') );
        add_action( 'wp_ajax_getServices', array( $this, 'getServices') );
        add_action( 'wp_ajax_getItemIdFromMeta', array( $this, 'getItemIdFromMeta') );
        add_action( 'wp_ajax_getItemIdFromMeta', array( $this, 'getItemIdFromMeta') );
        add_action( 'woocommerce_order_status_completed', array($this, 'mysite_completed'));
    }

    function mysite_completed($order_id) {
        global $wpdb;
        $order = new WC_Order( $order_id );
        $items = $order->get_items();
        $product_id = null;
        $item_id = null;
        foreach ( $items as $item ) {
            $product_id = $item['product_id'];
            $item_id = $item['item_id'];
        }
        error_log("order id is ".$order_id. "item_id is ".$item_id);
    }

    function getItemIdFromMeta(){
        global $wpdb;
        $order = new WC_Order(4033);
        $items = $order->get_items();
        echo json_encode($items);
        die();
    }

    function orderFollowers(){
        $apiService = new ApiService();
        $data = [
            "service"=>0,
            "link"=>0,
            "quantity"=>0,
            "username"=>0,
        ];
    }
}