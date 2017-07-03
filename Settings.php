
<?php

function instapromo_settings_page_html(){
    ?>
    <form action='options.php' method='post'>

        <h2>Instapromo</h2>

        <?php
        settings_fields( 'IP_access_token' );
        do_settings_sections( 'insta_promo_settings' );
        submit_button();
        ?>

    </form>
    <?php
}
