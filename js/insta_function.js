/**
 * Created by osho on 6/12/17.
 */


function getJsonFromUrl() {
    var query = location.search.substr(1);
    var result = {};
    query.split("&").forEach(function(part) {
        var item = part.split("=");
        result[item[0]] = decodeURIComponent(item[1]);
    });
    return result;
}


function getBaseUrl() {
    var getUrl = window.location;
    var baseUrl = getUrl .protocol + "//" + getUrl.host + "/" + getUrl.pathname.split('/')[1];
    return baseUrl
}

function getUserData($, callback) {
    var params = getJsonFromUrl();
    var username = params._username;
    $.get( "http://localhost:5000/plugin", function( data ) {
        callback(data)
    });
}

function appendDetailData($, counts) {
    $("#detail-table").append("<tr><td>Followers</td><td>"+counts.followed_by+"</td></tr>"+
        "<tr><td>Following</td><td>"+counts.follows+"</td></tr><tr><td>Posts</td>" +
        "<td>"+counts.media+"</td></tr>"
    )
}

function appendImage($, data) {
    $(".profile-img").attr("src",data.profile_picture);
}


function getProductId($) {
    var productId = $(".btn").attr("id");
    return productId;
}

function getFollowerQuantity($){
    var followerCount= $("#f_count").val();
    return followerCount;
}

function getOrderType($) {
    var orderType = $("#order_type").val();
    return orderType;
}

function  sendRequest($, data, console_message) {
    $.post(InstaAjax.ajaxurl, data, function(response) {
        console.log(console_message + ' ' + response);
    });
}

function saveFollowerOrderToCart($) {
    var productId = getProductId($);
    var followerQuantity = getFollowerQuantity($);
    var orderType = getOrderType($);
    var username = getJsonFromUrl()._username;
    var data = {
        action: 'addItemToCart',
        product_id: productId,
        username: username,
        order_type: orderType,
        follower_quantity: followerQuantity
    };
    sendRequest($, data, 'follower order');
}

function getOrderFromCart($) {
    var data = {
        action: 'getCartItems'
    };

    sendRequest($, data, "order from cart");
}

function placeOrder($) {
    var data = {
        action: 'placeOrder'
    };
    sendRequest($, data, "placed order");
}

function getMeta($) {
    var data = {
        action: 'getMetaData'
    };

    sendRequest($, data, "get meta data of order");
}

function fromSession($) {
    var data = {
        action: 'getFromSession'
    };
    sendRequest($, data, "get data from session");
}

function getOrderData($) {
    var data = {
        action: 'getOrderData'
    };

    sendRequest($, data, "get order data");
}

function notify($) {
    $('.notify-fixed').fadeIn();
    setTimeout(function () {
        $('.notify').fadeOut(3000);
    },3000)
}

(function($) {
    $(document).ready(function() {
        getUserData($, function (data) {
            console.log(data.data);
            appendDetailData($, data.data.counts);
            appendImage($, data.data);
        });

        $('.btn').on("click", function () {
            var productID = getProductId($);
            //saveFollowerOrderToCart($);
            //getMeta($);
            //placeOrder($);
            //getOrderData($);
            //getOrderFromCart($);
            //fromSession($);
            notify($);
        });
    });
})(jQuery);

