<div class="follower-order">
    <div class="notify-fixed">
        <div class="notify">
            <p>Product successfully added to cart</p>
        </div>
    </div>
    <div class="row">
        <div class="col s12 m7 l7">
            <div class="col s5 m5 l5">
                <div class="image-wrapper">
                    <img class="profile-img" src="">
                </div>
            </div>
            <div class="col s7 m7 l7">
                <div class="profile-data">
                    <table class="summary-table">
                        <tbody id="detail-table">
                            
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="col s12 m5 l5">
                <form>
                    <input type="hidden" id="f_count" value="<?php echo $follower_quantity ?>">
                    <input type="hidden" id="order_type" value="<?php echo $order_type ?>">
                    <input placeholder="enter number of followers" class="browser-default" type="number" name="_follower_count">
                </form>
            </div>
        </div>
        <div class="col s12 m5 l5">
            <table class="bordered">
                <thead>
                <h5 class="grey-text">Summary</h5>
                </thead>

                <tbody>
                <tr>
                    <td>1000 followers</td>
                    <td>$0.87</td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>$3.76</td>
                </tr>
                </tbody>
            </table>
            <div class="cart-button">
                <button id="<?php echo $product_id?>" class="btn btn-flat grey right-align">Confirm Order</button>
                <button class="btn btn-flat grey right-align">Proceed to checkout</button>
            </div>
        </div>
    </div>
</div>
