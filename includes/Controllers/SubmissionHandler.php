<?php

namespace BuyMeCoffee\Controllers;

use BuyMeCoffee\Helpers\ArrayHelper;
use BuyMeCoffee\Helpers\PaymentHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class SubmissionHandler
{
    public function handleSubmission()
    {
        if (!isset($_REQUEST['form_data'])) {
            wp_send_json_error(array(
                'message' => __("Invalid form data", 'buy-me-coffee')
            ), 403);
        }

        parse_str($_REQUEST['form_data'], $form_data);

        $paymentMethod = isset($_REQUEST['payment_method']) ? sanitize_text_field($_REQUEST['payment_method']) : 'paypal';
        $paymentTotal = isset($_REQUEST['payment_total']) ? intval($_REQUEST['payment_total']) : 0;
        $quantity = isset($_REQUEST['coffee_count']) ? intval($_REQUEST['coffee_count']) : 1;
        $currency = isset($_REQUEST['currency']) ? sanitize_text_field($_REQUEST['currency']) : false;

        if (!$currency) {
            $currency = PaymentHelper::getCurrency();
        }

        $form_data['payment_method'] = $paymentMethod;
        $form_data['payment_total'] = $paymentTotal;

        $supportersName = sanitize_text_field(ArrayHelper::get($form_data, 'wpm-supporter-name', 'Anonymous'));
        $supportersEmail = sanitize_email(ArrayHelper::get($form_data, 'wpm-supporter-email'));
        $supportersMessage = sanitize_text_field(ArrayHelper::get($form_data, 'wpm-supporter-message'));

        $hash = sanitize_text_field($this->getHash());
        $reference = sanitize_text_field(ArrayHelper::get($form_data, '__buymecoffee_ref', ''));

        $entries = array(
            'supporters_name' => $supportersName,
            'supporters_email' => $supportersEmail,
            'supporters_message' => $supportersMessage,
            'form_data_raw' => maybe_serialize($form_data),
            'currency' => $currency,
            'payment_method' => $paymentMethod,
            'payment_status' => 'pending',
            'entry_hash' => $hash,
            'payment_total' => $paymentTotal,
            'coffee_count' => $quantity,
            'reference' => $reference,
            'status' => 'new',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $entries = apply_filters('buymecoffee_supporter_entries', $entries);

        do_action('buymecoffee_before_supporters_data_insert', $entries);

        // DO ENTRIES INSERT
        $entryId = wpmBmcDB()->table('buymecoffee_supporters')->insert($entries);

        do_action('buymecoffee_after_supporters_data_insert', $entries);

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

         $transactionTable = wpmBmcDB()->table('buymecoffee_transactions');
         $transactionTable->insert($transaction);

         $transactionId =
             $transactionTable->select(['id'])
            ->where('entry_hash', $hash)
            ->where('entry_id', $entryId)
            ->first();

         if ($paymentTotal > 0) {
            do_action('buymecoffee_make_payment_' . $paymentMethod, $transactionId->id, $entryId, $form_data);
        }

        wp_send_json_success(array(
            'message' => __('Thanks for your support! <3', 'buy-me-coffee'),
            'submission_id' => $entryId
        ), 200);

    }

    private function getHash()
    {
        $prefix = 'buymecoffee_' . time();
        $uid = uniqid($prefix);
        // now let's make a unique number from 1 to 999
        $uid .= wp_rand(1, 999);
        $uid = str_replace(array("'", '/', '?', '#', "\\"), '', $uid);
        return $uid;
    }

}
