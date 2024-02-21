<?php

namespace buyMeCoffee\Builder\Methods\PayPal;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class PayPalSettings
{
    public static function get($key = null)
    {
        $settings = get_option('buymecoffee_payment_settings_paypal', array());

        $defaults = array(
            'enable' => 'no',
            'payment_mode' => 'test',
            'payment_type' => 'standard',
            'test_public_key' => '',
            'test_secret_key' => '',
            'live_public_key' => '',
            'live_secret_key' => '',
            'paypal_email' => '',
            'disable_ipn_verification' => 'no',
        );

        $data = wp_parse_args($settings, $defaults);
        return $key && isset($data[$key]) ? $data[$key] : $data;
    }

    public static function getKeys($key = null)
    {
        $settings = self::get();

        if ($settings['payment_mode'] == 'test') {
            $data = array(
                'secret' => $settings['test_secret_key'],
                'public' => $settings['test_public_key']
            );
        } else {
            $data = array(
                'secret' => $settings['live_secret_key'],
                'public' =>$settings['live_public_key']
            );
        }

        return $key && isset($data[$key]) ? $data[$key] : $data;

    }
}