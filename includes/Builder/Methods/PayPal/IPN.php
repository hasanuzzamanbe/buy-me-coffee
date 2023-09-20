<?php

namespace BuyMeCoffee\Builder\Methods\PayPal;

use BuyMeCoffee\Models\Supporters;
use BuyMeCoffee\Models\Transactions;

class IPN
{
    public function ipnVerificationProcess()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] != 'POST') {
            return;
        }

        header("HTTP/1.1 200 OK");

        $post_data = '';
        if (ini_get('allow_url_fopen')) {
            $post_data = file_get_contents('php://input');
        } else {
            ini_set('post_max_size', '12M');
        }
        $encoded_data = 'cmd=_notify-validate';
        $arg_separator = ini_get('arg_separator.output');
        if ($post_data || strlen($post_data) > 0) {
            $encoded_data .= $arg_separator . $post_data;
        } else {
            // phpcs:ignore WordPress.Security.NonceVerification.Missing
            $postData = $_POST;
            if (empty($postData)) {
                return;
            } else {
                foreach ($postData as $key => $value) {
                    $encoded_data .= $arg_separator . "$key=" . urlencode($value);
                }
            }
        }
        parse_str($encoded_data, $encoded_data_array);
        foreach ($encoded_data_array as $key => $value) {
            if (false !== strpos($key, 'amp;')) {
                $new_key = str_replace('&amp;', '&', $key);
                $new_key = str_replace('amp;', '&', $new_key);
                unset($encoded_data_array[$key]);
                $encoded_data_array[$new_key] = $value;
            }
        }
        $defaults = array(
            'txn_type' => '',
            'payment_status' => '',
            'custom' => ''
        );

        $encoded_data_array = wp_parse_args($encoded_data_array, $defaults);

        if (!is_array($encoded_data_array) && !empty($encoded_data_array)) {
            return;
        }
        $defaults = array(
            'txn_type' => '',
            'payment_status' => '',
            'custom' => ''
        );

        $encoded_data_array = wp_parse_args($encoded_data_array, $defaults);
        $payment_id = 0;

        $transactions = new Transactions();

        if (!empty($encoded_data_array['parent_txn_id'])) {
            $payment_id = $transactions->getByPaymentId($encoded_data_array['parent_txn_id']);
        } elseif (!empty($encoded_data_array['txn_id'])) {
            $payment_id = $transactions->getByPaymentId($encoded_data_array['txn_id']);
        }

        if (empty($payment_id)) {
            $payment_id = !empty($encoded_data_array['custom']) ? absint($encoded_data_array['custom']) : 0;
        }
        do_action('wpm_bmc_paypal_action_web_accept', $encoded_data_array, $payment_id);
        exit;
    }
}

