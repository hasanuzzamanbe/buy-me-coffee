<?php
namespace buyMeCoffee\Models;

class Buttons
{
    public function getButton($isDefault = false)
    {
        global $current_user;
        $defaults =  array(
                "yourName" => $current_user->display_name,
                "buttonText" => 'Buy Me Coffee',
                "enableMessage" => 'yes',
                "enableName" => 'yes',
                "enableEmail" => 'yes',
                "defaultAmount" => '5',
                "openMode" => 'modal',
                "methods" => [
                    'stripe', 'paypal'
                ],
                "advanced" => array (
                    "enable" => 'yes',
                    "bgColor" => 'rgba(250, 212, 0, 1)',
                    "color" => 'rgba(0, 0, 0, 1)',
                    "minWidth" => '180',
                    "textAlign" => 'center',
                    "minHeight" => '43',
                    "fontSize" => 21,
                    "radius" => 4
                )
        );

        if ($isDefault) {
            return $defaults;
        }

        $settings = get_option('wpm_bmc_payment_setting', array());
        return wp_parse_args($settings, $defaults);
    }
}
