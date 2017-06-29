<?php

/**
 * Created by PhpStorm.
 * User: osho
 * Date: 6/11/17
 * Time: 6:47 PM
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ApiService
{
    public $api_url = 'https://justanotherpanel.com/api/v2'; // API URL

    public $api_key = 'ccf7b3bfaed10ffd0f9751ddcde61ac4'; // Your API key

    public function order($data) { // add order
        $post = array_merge(array('key' => $this->api_key, 'action' => 'add'), $data);
        return $this->connect($post);
    }

    public function status($order_id) { // get order status
        return $this->connect(array(
            'key' => $this->api_key,
            'action' => 'status',
            'id' => $order_id
        ));
    }

    public function services() { // get services
        return $this->connect(array(
            'key' => $this->api_key,
            'action' => 'services',
        ));
    }

    public function balance() { // get balance
        return $this->connect(array(
            'key' => $this->api_key,
            'action' => 'balance',
        ));
    }

    private function connect($data){

        $response = wp_remote_post($this->api_url, [
            'body' => $data,
            'method' => 'POST',
            'timeout' => 45
        ]);
        return $response;
    }
}