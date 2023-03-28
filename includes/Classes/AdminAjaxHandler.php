<?php

namespace buyMeCoffee\Classes;
use buyMeCoffee\Models\Supporters;
use buyMeCoffee\Builder\Methods\PayPal\PayPal;
use buyMeCoffee\Helpers\ArrayHelper;
use buyMeCoffee\Controllers\PaymentHandler;
use buyMeCoffee\Builder\Methods\Stripe\Stripe;

use buyMeCoffee\Models\Buttons;

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
            'get_data' => 'getPaymentSettings',
            'save_payment_settings' => 'savePaymentSettings',

            'gateways' => 'getAllMethods',

            'save_settings' => 'saveSettings',
            'get_settings' => 'getSettings',
            'reset_template_settings' => 'resetDefaultSettings',
            
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

    public function getAllMethods()
    {

        return PaymentHandler::getAllMethods();

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
        $supporter = (new Supporters());
        if ($supporter->find($id)) {
            $supporter->delete($id);
            wp_send_json_success($supporter);
        }
        wp_send_json_error();
    }

    public function getPaymentSettings()
    {
        $method = sanitize_text_field(ArrayHelper::get($_REQUEST, 'method'));
        do_action('buy-me-coffee/get_payment_settings_' . $method);
    }

    public function resetDefaultSettings()
    {
        $settings = (new Buttons())->getButton($isDefault = true);
        update_option('wpm_bmc_payment_setting', $settings, false);
        do_action('wpm_bmc_after_reset_template', $settings);

        wp_send_json_success(array(
            'settings' => $settings,
            'message' => __("Settings successfully updated", 'buymecoffee')
        ), 200);

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
        $settings = (new Buttons())->getButton();

        wp_send_json_success(
           $settings, 200
        );
    }

    public function savePaymentSettings($data = array())
    {
        $settings = ArrayHelper::get($data, 'settings');
        $method = ArrayHelper::get($data, 'method');
        return (new PaymentHandler())->saveSettings($method, $settings);
    }
}
