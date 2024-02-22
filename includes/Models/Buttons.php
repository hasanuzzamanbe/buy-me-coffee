<?php


namespace BuyMeCoffee\Models;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Buttons
{
    public function getButton($isDefault = false)
    {
        global $current_user;
        $defaults = array(
            "yourName" => $current_user->display_name,
            "buttonText" => 'Buy Me Coffee',
            "enableMessage" => 'yes',
            "enableName" => 'yes',
            "enableEmail" => 'no',
            "defaultAmount" => '5',
            "custom_coffee" => 5,
            "openMode" => 'page',
            "currency" => 'USD',
            "advanced" => array(
                "image" => '',
                "enable" => 'yes',
                "bgColor" => 'rgba(250, 212, 0, 1)',
                "color" => 'rgba(0, 0, 0, 1)',
                "minWidth" => '180',
                "textAlign" => 'center',
                "minHeight" => '43',
                "fontSize" => 21,
                "radius" => 4,
                "quote" => '🤝 Helping hands work together to accomplish great things.',
            )
        );

        if ($isDefault) {
            return $defaults;
        }

        $settings = get_option('buymecoffee_payment_setting', array());
        return wp_parse_args($settings, $defaults);
    }
}
