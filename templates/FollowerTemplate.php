<div class="follower-order">
    <div class="notify-fixed">
        <div class="notify">
            <p>Order successfully confirmed!</p>
        </div>
    </div>
    <div class="row">
        <div class="container">
            <div class="handle-form col s12 m12 l12">
                <input id="IG-handle" name="instagram-handle" placeholder="your instagram username">
            </div>
        </div>

        <div class="col s12 m7 l7">
            <div class="row">
                <div class="col s5 m3 l3">
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
            </div>
            <div class="col s12 m5 l5">
                <form>
                    <input type="hidden" id="f_count" value="<?php echo $quantity ?>">
                    <input type="hidden" id="order_type" value="<?php echo $order_type ?>">
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
                    <td><?php echo $quantity?> followers</td>
                    <td>$<?php echo $_product_price ?></td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td>$<?php echo $_product_price ?></td>
                </tr>
                </tbody>
            </table>
            <div class="cart-button">
                <button id="<?php echo $product_id?>" class="btn btn-flat grey follow-confirm-order right-align">Confirm Order</button>
                <button class="btn btn-flat grey right-align"><a id="to-payment" href=""> Proceed to payment</a></button>
            </div>
        </div>
    </div>
</div>
