<?php

namespace buyMeCoffee\Classes;
use buyMeCoffee\Models\Supporters;

use buyMeCoffee\Controllers\ButtonControllers;

class AdminAjaxHandler
{
    public function registerEndpoints()
    {
        add_action('wp_ajax_wpm_bmc_admin_ajax', array($this, 'handleEndPoint'));
    }
    public function handleEndPoint()
    {
        $route = sanitize_text_field($_REQUEST['route']);

        $validRoutes = array(
            'get_data' => 'getStripeData',
            'get_paypal_Settings' => 'getPayPalSettings',
            'save_payment_settings' => 'savePaymentSettings',
            'save_settings' => 'saveSettings',
            'get_settings' => 'getSettings',
            'get_supporters' => 'getSupporters',
            'edit_supporter' => 'editSupporter',
            'delete_supporter' => 'deleteSupporter',
        );
        if (isset($validRoutes[$route])) {
            do_action('buy-me-coffee/doing_ajax_forms_' . $route);
            return $this->{$validRoutes[$route]}($_REQUEST);
        }
        do_action('buy-me-coffee/admin_ajax_handler_catch', $route);
    }

    public function getSupporters()
    {
        return (new Supporters())->index($_REQUEST);
    }

    public function editSupporter()
    {
        $id = sanitize_text_field($_REQUEST['id']);
        $supporter = (new Supporters())->find($id);
        if ($supporter) {
            $supporter->name = sanitize_text_field($_REQUEST['name']);
            $supporter->email = sanitize_text_field($_REQUEST['email']);
            $supporter->amount = sanitize_text_field($_REQUEST['amount']);
            $supporter->save();
            wp_send_json_success($supporter);
        }
        wp_send_json_error();
    }

    public function deleteSupporter()
    {
        $id = sanitize_text_field($_REQUEST['id']);
        $supporter = (new Supporters())->find($id);
        if ($supporter) {
            $supporter->delete();
            wp_send_json_success($supporter);
        }
        wp_send_json_error();
    }

    public function getStripeData()
    {
        // AccessControl::checkAndPresponseError('get_payment_settings', 'global');
        wp_send_json_success(array(
            'settings'       => $this->getStripeSettings(),
            'webhook_url'    => site_url() . '?wpm_bmc_stripe_listener=1'
        ), 200);
    }

    public function getMode()
    {
        $paymentSettings = $this->getStripeSettings();
        return ($paymentSettings['payment_mode'] == 'live') ? 'live' : 'test';
    }

    private function getStripeSettings()
    {
        $settings = get_option('wpm_bmc_stripe_payment_settings', array());
        $defaults = array(
            'enable' => 'no',
            'payment_mode'    => 'test',
            'live_pub_key'    => '',
            'live_secret_key' => '',
            'test_pub_key'    => '',
            'test_secret_key' => ''
        );
        return wp_parse_args($settings, $defaults);
    }

    public function getPayPalSettings()
    {
        wp_send_json_success(array(
            'settings'       => $this->paypalSettings(),
            'webhook_url'    => site_url() . '?wpm_bmc_paypal_listener=1'
        ), 200);

    }

    public function paypalSettings()
    {
        $settings = get_option('wpm_bmc_paypal_payment_settings', array());
        $defaults = array(
            'enable' => 'no',
            'payment_mode'    => 'test',
            'paypal_email'    => '',
            'disable_ipn_verification' => 'no',
        );
        return wp_parse_args($settings, $defaults);
    }


    public function saveSettings()
    {
        update_option('wpm_bmc_payment_setting', $_REQUEST['settings'], false);
        do_action('wpm_bmc_after_save_template', $_REQUEST['settings']);

        wp_send_json_success(array(
            'message' => __("Settings successfully updated", 'buymecoffee')
        ), 200);
    }

    public function getSettings()
    {

        $defaults = (new ButtonControllers())->getButton();
        $settings = get_option('wpm_bmc_payment_setting', array());

        $settings = wp_parse_args($settings, $defaults);

        wp_send_json_success(
           $settings, 200
        );
    }

    public function savePaymentSettings($data = array())
    {
        $method = $data['method'] . '_payment_settings';
        do_action('wpm_bmc_before_save_' .$method, $data);

        update_option('wpm_bmc_' .$method, $data['settings'], false);

        do_action('wpm_bmc_after_save_' . $method, $data);

        wp_send_json_success(array(
            'message' => __("Settings ({$data['method']}) successfully updated", 'buymecoffee')
        ), 200);
    }
}
