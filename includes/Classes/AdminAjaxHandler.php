<?php

namespace BuyMeCoffee\Classes;
use BuyMeCoffee\Models\Supporters;
use BuyMeCoffee\Builder\Methods\PayPal\PayPal;
use BuyMeCoffee\Helpers\ArrayHelper as Arr;
use BuyMeCoffee\Controllers\PaymentHandler;
use BuyMeCoffee\Builder\Methods\Stripe\Stripe;
use BuyMeCoffee\Helpers\PaymentHelper;
use BuyMeCoffee\Helpers\Currencies;

use BuyMeCoffee\Models\Buttons;

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
            'get_supporter' => 'getSupporter',
            'delete_supporter' => 'deleteSupporter',
            'update_payment_status' => 'updatePaymentStatus'

        );
        if (isset($validRoutes[$route])) {
            do_action('buy-me-coffee/doing_ajax_forms_' . $route);
            return $this->{$validRoutes[$route]}($_REQUEST);
        }
        do_action('buy-me-coffee/admin_ajax_handler_catch', $route);
    }

    public function getAllMethods()
    {
        $methods = PaymentHandler::getAllMethods();
        wp_send_json_success($methods, 200);
    }

    public function updatePaymentStatus()
    {
        $id = intval($_REQUEST['id']);
        $status = sanitize_text_field($_REQUEST['status']);
        $supporter = wpmBmcDB()->table('wpm_bmc_supporters')->where('id', $id)->update(['payment_status' => $status]);
        if ($supporter) {
            wp_send_json_success($supporter, 200);
        }
    }

    public function getSupporter()
    {
        $id = intval($_REQUEST['id']);
        $supporter = (new Supporters())->find($id);

        $supporter->supporters_image = get_avatar_url($supporter->supporters_email);

        if ($supporter) {
            wp_send_json_success($supporter, 200);
        }
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
            $supporter->name = sanitize_text_field(Arr::get($_REQUEST, 'name', ''));
            $supporter->email = sanitize_text_field(Arr::get($_REQUEST, 'email', ''));
            $supporter->amount = sanitize_text_field(Arr::get($_REQUEST, 'amount'));
            $supporter->save();
            wp_send_json_success($supporter, 200);
        }
        wp_send_json_error();
    }

    public function deleteSupporter()
    {
        $id = sanitize_text_field($_REQUEST['id']);
        $supporter = (new Supporters());
        if ($supporter->find($id)) {
            $supporter->delete($id);
            wp_send_json_success($supporter, 200);
        }
        wp_send_json_error();
    }

    public function getPaymentSettings()
    {
        $method = sanitize_text_field(Arr::get($_REQUEST, 'method'));
        do_action('buy-me-coffee/get_payment_settings_' . $method);
    }

    public function resetDefaultSettings()
    {
        $settings = (new Buttons())->getButton($isDefault = true);
        update_option('wpm_bmc_payment_setting', $settings, false);
        do_action('wpm_bmc_after_reset_template', $settings);

        wp_send_json_success(array(
            'settings' => $settings,
            'message' => __("Settings successfully updated", 'buy-me-coffee')
        ), 200);

    }

    public function saveSettings()
    {
        $settings = $_REQUEST['settings'] ?: array();

        $data = $this->sanitizeTextArray($settings);
        update_option('wpm_bmc_payment_setting', $data, false);
        do_action('wpm_bmc_after_save_template', $data);

        wp_send_json_success(array(
            'message' => __("Settings successfully updated", 'buy-me-coffee')
        ), 200);
    }

    public function sanitizeTextArray($data)
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $k => $v) {
                    $data[$key][$k] = sanitize_text_field($v);
                }
            } else {
                $data[$key] = sanitize_text_field($value);
            }
        }
        return $data;
    }

    public function getSettings()
    {
        $settings = (new Buttons())->getButton();

        wp_send_json_success(
            array(
                'template' => $settings,
                'currencies' => Currencies::all()
            ),
        200
        );
    }

    public function savePaymentSettings($data = array())
    {
        $settings = Arr::get($data, 'settings');
        $method = Arr::get($data, 'method');
        return (new PaymentHandler())->saveSettings($method, $settings);
    }
}
