

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

    $.ajax({
        url: "https://www.instagram.com/osho_refueled?__a=1",
        type: "GET",
        crossDomain: true,
        success: function(data){
            console.log("this is the "+data);
            callback(data);
        }
    });
}

function appendDetailData($, data) {
    $("#detail-table").empty().append("<tr><td>Followers</td><td>"+data.followed_by.count+"</td></tr>"+
        "<tr><td>Following</td><td>"+data.follows.count+"</td></tr><tr><td>Posts</td>" +
        "<td>"+data.media.count+"</td></tr>"
    );
}

function appendImage($, data) {
    $(".profile-img").attr("src",data.profile_pic_url);
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
    var username = $("#IG-handle").val();
    var data = {
        action: 'addItemToCart',
        product_id: productId,
        username: username,
        order_type: orderType,
        follower_quantity: followerQuantity
    };
    $.post(InstaAjax.ajaxurl, data, function(response) {
        var res = JSON.parse(response);
        var url = res.checkout_url;
        console.log(url);
        $("#to-payment").attr("href", url);
    });
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

////////////////////////////////////////////////
// Javascript for ordering likes
////////////////////////////////////////////////
function getUserMedia($, callback) {
    var params = getJsonFromUrl();
    var username = params._username;
    $.get( "https://www.instagram.com/"+username+"?__a=1", function( data ) {
        var id = data.user.id;
        $.get("https://api.instagram.com/v1/users/"+id+"/media/recent/?access_token="+instagram_token
            , function (data) {
            callback(data);
        })
    });
}

function getInputValues($) {
    var totalLikes = [];
    totalLikes = $(".likes-forms").map(function () {
        return $(this).val();
    }).get();
    return totalLikes;
}

function getRemainingLikes($) {
    var limit = $(".limit").val();
    var total = calculateTotalLikes($);
    var remaining = limit - total;
    return remaining;
}

function calculateTotalLikes($) {
    var likesArray = getInputValues($);
    var total = 0;
    for(var i=0; i<likesArray.length; i++){
        if((likesArray[i]) == ""){
            continue;
        }
        total = total + parseInt(likesArray[i]);
    }
    return total;
}

function updateTotalLikes($) {
    var totalLikes = calculateTotalLikes($);
    var remaining = getRemainingLikes($);
    $(".remaining").html(remaining);
}

function appendMedia($, url, link){
    $("#image-col").append(
    "<div class='col s6 m3 l3'><div class='insta-img-wrapper'><img alt="+link+" src="+url+" class='insta-img'>"+
    "<input class='likes-forms' placeholder='likes' type='number'></div></div>"
    );
}

function getSelectedData($) {
    var data = [];
    $("body .highlight").each(function() {
        var url = $(this).attr("alt");
        var likes = $(this).next().val();
        data.push({"url": url, "likes": likes})
    });
    return data;
}

function highlightImage($) {
    console.log("clicked");

    $('body').on('click', 'img', function () {
        console.log("image clicked");
        $(this).toggleClass("highlight");
    });
}

function getProductIdLikes($) {
    var productId = $(".likes-confirm-button").attr("id");
    return productId;
}

function getOrderTypeLikes($) {
    var orderType = $("#order-type-likes").val();
    return orderType;
}



function saveLikesOrderToCart($) {
    var productId = getProductIdLikes($);
    var orderType = getOrderTypeLikes($);
    var username = getJsonFromUrl()._username;
    var selectedData = getSelectedData($);
    var data = {
        action: 'addLikesItemToCart',
        product_id: productId,
        username: username,
        order_type: orderType,
        order_data: selectedData
    };
    console.log(data);
    sendRequest($, data, 'likes order');
}


function getUserInstagramData($, callback) {
    var username = $("#IG-handle").val();
    var data = {
        action: 'getUserInstagramData',
        username:username
    };

    $.post(InstaAjax.ajaxurl, data, function(response) {
        var newData = JSON.parse(response);
        console.log(newData);
        callback(newData);
    });
}


(function($) {
    $(document).ready(function() {

        var typingTimer;                //timer identifier
        var doneTypingInterval = 3000;  //time in ms, 5 second for example
        var $input = $('#IG-handle');

        //on keyup, start the countdown
        $input.on('keyup', function () {
            clearTimeout(typingTimer);
            $("#image-col").empty();
            typingTimer = setTimeout(doneTyping, doneTypingInterval);
        });

        //on keydown, clear the countdown
        $input.on('keydown', function () {
            clearTimeout(typingTimer);
            $("#image-col").empty();
        });

        //user is "finished typing," do something
        function doneTyping () {
            getUserInstagramData($, function (data) {
                appendDetailData($, data.user);
                appendImage($, data.user);

                var userData = data.user.media.nodes;
                for(var i=0; i<userData.length; i++){
                    appendMedia($, userData[i].thumbnail_src, "https://instagram.com/p/"+userData[i].code);
                }
            });
        }

        /**
         * Followers
         */

        $('.follow-confirm-order ').on("click", function () {
            saveFollowerOrderToCart($);
            notify($);
        });


        /**
         * Likes
         */

        $("body").on("change paste keyup .likes-forms", function() {
            updateTotalLikes($);
            var remaining = getRemainingLikes($);
            if( remaining === 0){
                $(".likes-confirm-button").prop("disabled", false);
            } else {
                $(".likes-confirm-button").prop("disabled", true);
            }
        });

        highlightImage($);

        $(".likes-confirm-button").on("click", function () {
            saveLikesOrderToCart($);
        });

        $(function() {
            $("#image-col input[type=text]").each(function() {
                if(!isNaN(this.value)) {
                    alert(this.value + " is a valid number");
                }
            });
            return false;
        });


    });
})(jQuery);

