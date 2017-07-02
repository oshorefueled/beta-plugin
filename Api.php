<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


class Api
{

    private $baseUrl = "https://instagram.com/";

    function __construct()
    {
        add_action( 'wp_ajax_getServices', array( $this, 'getServices') );
        add_action( 'wp_ajax_getServices', array( $this, 'getServices') );
        add_action( 'woocommerce_order_status_completed', array($this, 'order_completed'));
    }

    function order_completed($order_id) {
        //order type 1 = followers
        //order type 2 = likes

        global $wpdb;
        $item_id = $this->getItemIdFromMeta($order_id);
        $order_data = $this->getOrderData($item_id, "IP_session");
        $unserialized_data = unserialize($order_data[0]);
        $this->orderFollowers($order_id,$item_id, $unserialized_data);

        $order_type = (int) $unserialized_data["order_type"];
        if($order_type == 1){
            $follower_quantity = (int) $unserialized_data["follower_quantity"];
            if($follower_quantity < 100){
                error_log("order ".$order_id." followers count less than 100");
                $order = new WC_Order($order_id);
                $order->update_status("processing");
                var_dump("order can't be process because follower quantity is less than 100");
                die();
            }
            $this->orderFollowers($order_id,$item_id, $unserialized_data);
        }

        if($order_type == 2){
            $this->orderLikes($order_id, $item_id, $unserialized_data);
        }
        error_log("order id is ".$order_id. "username is ". $unserialized_data["username"]);
    }

    function getItemIdFromMeta($order_id){
        global $wpdb;
        $order = new WC_Order($order_id);
        $items = $order->get_items();
        $item = 0;
        foreach ($items as $key => $items){
            $item = $key;
            break;
        }
        return $item;
    }

    function getOrderData($item_id, $session_key){
        $meta = wc_get_order_item_meta($item_id);
        return $meta[$session_key];
    }

    function order($data){
        $apiService = new ApiService();
        $response = $apiService->order($data);
        return $response;
    }

    function orderFollowers($order_id,$item_id, $data){
        $follower_quantity = (int) $data["follower_quantity"];
        $username = $data["username"];
        switch (true){
            case $follower_quantity == 100:
                $response = $this->order(
                    [
                        "service" => 14,
                        "quantity"=> $follower_quantity,
                        "link"=> $username
                    ]
                );
                $this->errorCheck($response, $order_id);
                wc_add_order_item_meta($item_id, "instagram_order_id", print_r($response));
                error_log("order successful! id of order ".$order_id. "is ". print_r($response));
                break;

            case  $follower_quantity >= 200:
                $response = $this->order([
                    "service" => 84,
                    "quantity"=> $follower_quantity,
                    "link"=> $username
                ]);
                $this->errorCheck($response, $order_id);
                error_log("order successful! id of order ".$order_id. "is ". print_r($response));
                break;

            default:
                error_log("order ".$order_id. " didn't pass followers count check");
                break;
        }

    }

    function orderLikes($order_id,$item_id, $data){
        
        $order_data = $data["order_data"];

        for($i=0; $i<count($order_data); $i++){
            $likes = $order_data[$i]["likes"];
            $link = $order_data[$i]["url"];

            switch (true){
                case $likes >= 100 && $likes < 4001:
                    $response = $this->order(
                        [
                            "service" => 100,
                            "quantity"=> $likes,
                            "link"=> $link,
                        ]
                    );
                    $this->errorCheck($response, $order_id);
                    wc_add_order_item_meta($item_id, "instagram_order_id", print_r($response));
                    error_log("order successful! id of order ".$order_id. "is ". print_r($response));
                    break;

                case $likes > 4000 && $likes < 15001:
                    $response = $this->order([
                        "service" => 255,
                        "quantity"=> $likes,
                        "link"=> $link
                    ]);
                    $this->errorCheck($response, $order_id);
                    error_log("order successful! id of order ".$order_id. "is ". print_r($response));
                    break;

                default:
                    error_log("order ".$order_id. " likes less than 100");
                    break;
            }

        }
    }
    
    function errorCheck($response, $order_id){
        if(array_key_exists("error", $response)){
            error_log("error in order ". $order_id. " :". print_r($response));
            $order = new WC_Order($order_id);
            $order->update_status('processing');
            die();
        }
    }
}