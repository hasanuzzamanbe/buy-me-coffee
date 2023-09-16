<?php

namespace BuyMeCoffee\Controllers;

use BuyMeCoffee\Helpers\ArrayHelper;

class PaymentHandler
{
    public static function getAllMethods()
    {
        $methods = apply_filters('wpm_bmc_get_all_methods', []);
        return $methods;
    }

    public function saveSettings($method, $settings)
    {
        $settings = apply_filters('wpm_bmc_before_save_' . $method, $settings);

        update_option('wpm_bmc_payment_settings_' . $method, $settings, false);

        do_action('wpm_bmc_after_save_' . $method, $settings);

        wp_send_json_success(array(
            'message' => __("Settings ({$method}) successfully updated", 'buy-me-coffee')
        ), 200);

    }

}