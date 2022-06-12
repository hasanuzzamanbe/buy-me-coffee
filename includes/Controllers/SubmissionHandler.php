<?php
namespace buyMeCoffee\Controllers;

use buyMeCoffee\Helpers\ArrayHelper;
class SubmissionHandler
{
    public function handleSubmission()
    {
        parse_str($_REQUEST['form_data'], $form_data);

        $paymentMethod = ArrayHelper::get($_REQUEST, 'payment_method');
        $paymentTotal = intval(ArrayHelper::get($_REQUEST, 'payment_total'));

        $form_data['payment_method'] = $paymentMethod;
        $form_data['payment_total'] = intval($paymentTotal);

        $supportersName = ArrayHelper::get($form_data, 'wpm-supporter-name');
        $supportersEmail = ArrayHelper::get($form_data, 'wpm-supporter-email');
        $supportersMessage = ArrayHelper::get($form_data, 'wpm-supporter-message');
        $hash = $this->getHash();

        $entries = array(
            'supporters_name' => sanitize_text_field($supportersName),
            'supporters_email' => sanitize_email($supportersEmail),
            'supporters_message' => sanitize_text_field($supportersMessage),
            'form_data_raw' => maybe_serialize($form_data),
            'currency' => 'USD',
            'payment_method' => sanitize_text_field($paymentMethod),
            'payment_status' => 'pending',
            'entry_hash' => sanitize_text_field($hash),
            'payment_total' => $paymentTotal,
            'status' => 'new',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $entries = apply_filters('wpm_buymecoffee_supporter_entries', $entries);

        do_action('wpm_buymecoffee_BEFORE_supporters_data_insert', $entries);

        // DO ENTRIES INSERT
        $entryId = wpmBmcDB()->table('wpm_bmc_supporters')->insert($entries);

        do_action('wpm_buymecoffee_after_supporters_data_insert', $entries);

        $transaction = array(
            'entry_hash' => sanitize_text_field($hash),
            'entry_id' => $entryId,
            'charge_id' => '',
            'payment_method' => sanitize_text_field($paymentMethod),
            'payment_total' => intval($paymentTotal),
            'currency' => 'USD',
            'status' => 'pending',
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql'),
        );

        $transactionId = wpmBmcDB()->table('wpm_bmc_transactions')->insert($transaction);

        if ($paymentTotal > 0) {
            do_action( 'wpm_buymecoffee_make_payment_' . $paymentMethod, $transactionId, $entryId, $form_data);
        }

        wp_send_json_success(array(
            'message'       => __('Thanks for your support! <3', 'wppayform'),
            'submission_id' => $entryId
        ), 200);

    }

    private function getHash()
    {
        $prefix = 'wpm_bmc_' . time();
        $uid = uniqid($prefix);
        // now let's make a unique number from 1 to 999
        $uid .= mt_rand(1, 999);
        $uid = str_replace(array("'", '/', '?', '#', "\\"), '', $uid);
        return $uid;
    }
}