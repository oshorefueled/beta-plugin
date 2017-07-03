<div class="insta-likes">
    <div class="notify-fixed">
        <div class="notify">
            <p>order confirmed! Proceed to checkout</p>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="handle-form">
                <input id="IG-handle" name="instagram-handle" placeholder="awesome_username">
            </div>
            <div id="image-col" class="col s7">
                <!-- images to be appended here -->
            </div>
            <div class="col s5">
                <table class="bordered">
                    <input class="limit" type="hidden" value="<?php echo $likes ?>">
                    <input id="order-type-likes" type="hidden" value="<?php echo $order_type ?>">
                    <thead>
                    <h6>You have <span class="remaining"><?php echo $likes ?></span> likes left</h6>
                    <h5 class="grey-text">Summary</h5>
                    </thead>

                    <tbody>
                    <tr>
                        <td>Total likes</td>
                        <td id="total-likes"><?php echo $likes ?></td>
                    </tr>
                    <tr>
                        <td>Total</td>
                        <td>$<?php echo $_product_price ?></td>
                    </tr>
                    </tbody>
                </table>
                <div class="cart-button">
                    <button disabled id="<?php echo $product_id ?>" class="btn btn-flat red right-align likes-confirm-button">Confirm Order</button>
                    <button class="btn btn-flat red right-align proceed-button">Proceed to payment</button>
                </div>
            </div>

        </div>
    </div>
</div>