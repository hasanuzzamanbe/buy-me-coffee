<?php

namespace BuyMeCoffee\Builder\Methods\PayPal;

use BuyMeCoffee\Models\Supporters;
use BuyMeCoffee\Models\Transactions;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

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

        //if ipn verification enabled then verify the ipn
        if (false) {
            $validate_ipn = wp_unslash($_POST); // WPCS: CSRF ok, input var ok.
            $validate_ipn['cmd'] = '_notify-validate';
            // Validate the IPN
            $remote_post_vars = array(
                'timeout' => 60,
                'redirection' => 5,
                'httpversion' => '1.1',
                'compress'    => false,
                'decompress'  => false,
                'user-agent' => 'WPM BMC IPN Verification/' . '1.0.0' . '; ' . get_bloginfo('url'),
                'body' => $validate_ipn
            );

            $api_response = wp_safe_remote_post($this->getPaypalRedirect(true, true), $remote_post_vars);

            if (is_wp_error($api_response)) {
                do_action('wpm_bmc/paypal_ipn_verification_failed', $remote_post_vars, $encoded_data_array);
                return;
            }
            if (wp_remote_retrieve_body($api_response) !== 'VERIFIED') {
                do_action('wpm_bmc/paypal_ipn_not_verified', $api_response, $remote_post_vars, $encoded_data_array);
                return;
            }
        }

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
            $payment_id = $transactions->getByPaymentId($encoded_data_array['parent_txn_id'],  'paypal');
        } elseif (!empty($encoded_data_array['txn_id'])) {
            $payment_id = $transactions->getByPaymentId($encoded_data_array['txn_id'], 'paypal');
        }

        if (empty($payment_id)) {
            $payment_id = !empty($encoded_data_array['custom']) ? absint($encoded_data_array['custom']) : 0;
        }

        do_action('wpm_bmc_paypal_action_web_accept', $encoded_data_array, $payment_id);
        exit;
    }

    private function getSettings()
    {
        $settings = get_option('wpm_bmc_payment_settings_paypal', []);
        return $settings;
    }

    private function isTestMode()
    {
        $settings = get_option('wpm_bmc_payment_settings_paypal', []);
        if (isset($settings['payment_mode']) && $settings['payment_mode'] == 'live') {
            return false;
        }
        return true;
    }

    private function getPaypalRedirect($ssl_check = false, $ipn = false)
    {
        $protocol = 'http://';
        if (is_ssl() || !$ssl_check) {
            $protocol = 'https://';
        }

        // Check the current payment mode
        if ($this->isTestMode()) {
            // Test mode
            if ($ipn) {
                $paypal_uri = 'https://ipnpb.sandbox.paypal.com/cgi-bin/webscr';
            } else {
                $paypal_uri = $protocol . 'www.sandbox.paypal.com/cgi-bin/webscr';
            }
        } else {
            // Live mode
            if ($ipn) {
                $paypal_uri = 'https://ipnpb.paypal.com/cgi-bin/webscr';
            } else {
                $paypal_uri = $protocol . 'www.paypal.com/cgi-bin/webscr';
            }
        }
        return apply_filters('wpm_bmc_ipn/paypal_url', $paypal_uri, $ssl_check, $ipn);
    }
}

