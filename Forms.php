<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class Forms
{
    function __construct($form_target)
    {
        $this->formTarget = $form_target;
        add_shortcode("insta_promo", array($this, "formTemplate"));
        add_shortcode("insta_perm", array($this, "getPermalink"));
    }

    function formTemplate($attr, $content=null)
    {
        ob_start();
        extract($attr);
        
        if(array_key_exists("form_type", $attr)){
            $formType = $attr["form_type"];
            switch ($formType) {
                case "email_only":
                    include plugin_dir_path(__FILE__).'templates/EmailTemplate.php';
                    break;
                case "all":
                    include plugin_dir_path(__FILE__).'templates/FormTemplate.php';
                    break;
                default:
                    include plugin_dir_path(__FILE__).'templates/FormTemplate.php';
            }
        }
        return ob_get_clean();
    }

    function getPermalink()
    {
        return var_dump($_GET);
    }

}

