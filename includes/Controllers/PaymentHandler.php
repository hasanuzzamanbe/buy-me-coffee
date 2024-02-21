<?php

namespace BuyMeCoffee\Controllers;

use BuyMeCoffee\Helpers\ArrayHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class PaymentHandler
{
    public static function getAllMethods()
    {
        $methods = apply_filters('buymecoffee_get_all_methods', []);
        return $methods;
    }

    public function saveSettings($method, $settings)
    {
        $settings = apply_filters('buymecoffee_before_save_' . $method, $settings);

        update_option('buymecoffee_payment_settings_' . $method, $settings, false);

        do_action('buymecoffee_after_save_' . $method, $settings);

        wp_send_json_success(array(
            'message' => "Settings $method successfully updated"
        ), 200);

    }

}