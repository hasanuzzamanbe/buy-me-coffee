<?php

namespace BuyMeCoffee\Controllers;

use BuyMeCoffee\Helpers\ArrayHelper;
use BuyMeCoffee\Helpers\PaymentHelper;

class SubmissionHandler
{
    public function handleSubmission()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $request = $_REQUEST;
        parse_str($request['form_data'], $form_data);

        $paymentMethod = ArrayHelper::get($request, 'payment_method');
        $paymentTotal = intval(ArrayHelper::get($request, 'payment_total'));
        $quantity = ArrayHelper::get($request, 'coffee_count', 1);
        $currency = ArrayHelper::get($request, 'currency', false);

        if (!$currency) {
            $currency = PaymentHelper::getCurrency();
        }

        $form_data['payment_method'] = $paymentMethod;
        $form_data['payment_total'] = intval($paymentTotal);

        $supportersName = ArrayHelper::get($form_data, 'wpm-supporter-name', 'Anonymous');
        $supportersEmail = ArrayHelper::get($form_data, 'wpm-supporter-email');
        $supportersMessage = ArrayHelper::get($form_data, 'wpm-supporter-message');

        $hash = $this->getHash();
        $reference = ArrayHelper::get($form_data, '__buymecoffee_ref', '');

        $entries = array(
            'supporters_name' => sanitize_text_field($supportersName),
            'supporters_email' => sanitize_email($supportersEmail),
            'supporters_message' => sanitize_text_field($supportersMessage),
            'form_data_raw' => maybe_serialize($form_data),
            'currency' => sanitize_text_field($currency),
            'payment_method' => sanitize_text_field($paymentMethod),
            'payment_status' => 'pending',
            'entry_hash' => sanitize_text_field($hash),
            'payment_total' => $paymentTotal,
            'coffee_count' => intval($quantity),
            'reference' => sanitize_text_field($reference),
            'status' => 'new',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $entries = apply_filters('wpm_bmc_supporter_entries', $entries);

        do_action('wpm_bmc_before_supporters_data_insert', $entries);

        // DO ENTRIES INSERT
        $entryId = wpmBmcDB()->table('wpm_bmc_supporters')->insert($entries);

        do_action('wpm_bmc_after_supporters_data_insert', $entries);

        $transaction = array(
            'entry_hash' => sanitize_text_field($hash),
            'entry_id' => $entryId,
            'charge_id' => '',
            'payment_method' => sanitize_text_field($paymentMethod),
            'payment_total' => $paymentTotal,
            'currency' => 'USD',
            'status' => 'pending',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $transactionId = wpmBmcDB()->table('wpm_bmc_transactions')->insert($transaction);

        if ($paymentTotal > 0) {
            do_action('wpm_bmc_make_payment_' . $paymentMethod, $transactionId, $entryId, $form_data);
        }

        wp_send_json_success(array(
            'message' => __('Thanks for your support! <3', 'buy-me-coffee'),
            'submission_id' => $entryId
        ), 200);

    }

    private function getHash()
    {
        $prefix = 'wpm_bmc_' . time();
        $uid = uniqid($prefix);
        // now let's make a unique number from 1 to 999
        $uid .= wp_rand(1, 999);
        $uid = str_replace(array("'", '/', '?', '#', "\\"), '', $uid);
        return $uid;
    }

}
