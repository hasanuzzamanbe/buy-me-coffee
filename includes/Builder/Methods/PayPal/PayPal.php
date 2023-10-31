<?php

namespace BuyMeCoffee\Builder\Methods\PayPal;

use BuyMeCoffee\Helpers\ArrayHelper;
use BuyMeCoffee\Helpers\PaymentHelper;
use BuyMeCoffee\Models\Supporters;
use BuyMeCoffee\Models\Transactions;
use BuyMeCoffee\Builder\Methods\BaseMethods;
use BuyMeCoffee\Classes\Vite;

class PayPal extends BaseMethods
{
    public function __construct()
    {
        parent::__construct(
            'PayPal',
            'paypal',
            'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            Vite::staticPath() . 'images/PayPal.svg'
        );
        add_action('wpm_bmc_make_payment_paypal', array($this, 'makePayment'), 10, 3);
        add_action('wpm_bmc_paypal_action_web_accept', array($this, 'updateStatus'), 10, 2);
        add_action("wpm_bmc_ipn_endpoint_paypal", array($this, 'verifyIpn'), 10, 2);
    }

    public function sanitize($settings)
    {
        foreach ($settings as $key => $value) {
            if ($key === 'paypal_email') {
                $settings[$key] = sanitize_email($value);
            } else {
                $settings[$key] = sanitize_text_field($value);
            }
        }
        return $settings;
    }

    public function paymentConfirmation()
    {
        $this->updatePayment($_REQUEST);
    }
    public function verifyIpn()
    {
        (new IPN())->ipnVerificationProcess();
    }

    public function getPaymentSettings()
    {
        wp_send_json_success(array(
            'settings' => $this->getSettings(),
            'webhook_url' => site_url() . '?wpm_bmc_ipn_listener=1&method=paypal'
        ), 200);
    }

    public function getSettings($key = null)
    {
        return PayPalSettings::get($key);
    }

    public function maybeShowModal($transactions, $paypalSettings)
    {
        $paymentIntent = $this->modalPaymentIntent($transactions);
        $responseData = [
            'nextAction' => 'paypal',
            'hash' => $transactions->entry_hash,
            'actionName' => 'custom',
            'buttonState' => 'hide',
            'purchase_units' => $paymentIntent,
            'confirmation_url' => $this->successUrl($transactions),
            'message_to_show' => __('Payment Modal is opening, Please complete the payment', 'buy-me-coffee'),
        ];

        wp_send_json_success($responseData, 200);
    }

    public function modalPaymentIntent($transactions)
    {
        $total = $transactions->payment_total ? $transactions->payment_total : 5;
        $total = $total / 100;
        $currencyCode = $transactions->currency;
        $intent = [
            'reference_id' => $transactions->entry_hash,
            'amount' => [
                'value' => $total,
                'breakdown' => [
                    'item_total' => [
                        'currency_code' => $currencyCode,
                        'value' => $total,
                    ]
                ]
            ],
            'items' => array([
                'name' => 'Buy coffee for you',
                'unit_amount' => [
                    'currency_code' => $currencyCode,
                    'value' => $total,
                ],
                'quantity' => '1',
            ])
        ];

        return apply_filters('wpm_bmc_paypal_modal_payment_intent', $intent, $transactions);
    }

    public function makePayment($transactionId, $entryId, $form_data)
    {
        $paypalSettings = $this->getSettings();
        $transactionModel = new Transactions();
        $transaction = $transactionModel->find($transactionId);

        if ($paypalSettings['payment_type'] === 'pro') {
            $this->maybeShowModal($transaction, $paypalSettings);
        }

        $paypal_redirect = 'https://www.paypal.com/cgi-bin/webscr/?';

        if ($paypalSettings['payment_mode'] === 'test') {
            $paypal_redirect = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';
        }

        $supportersModel = new Supporters();
        $supporter = $supportersModel->find($entryId);
        $listener_url = apply_filters('wpm_bmc_paypal_ipn_url', site_url('?wpm_bmc_ipn_listener=1&method=paypal'), $supporter);

        $paypal_args = array(
            'cmd' => '_cart',
            'upload' => '1',
            'business' => $paypalSettings['paypal_email'],
            'email' => $supporter->supporters_email,
            'no_shipping' => '1',
            'no_note' => '1',
            'currency_code' => $supporter->currency ?? 'USD',
            'charset' => 'UTF-8',
            'custom' => $transactionId,
            'return' => $this->successUrl($supporter),
            'notify_url' => $listener_url,
            'cancel_return' => $this->failedUrl($supporter),
            'image_url' => '',
        );

        $payment_item = $this->cartItems($transaction);

        if (!$payment_item) {
            return;
        }
        $paypal_args = array_merge($payment_item, $paypal_args);

        $paypal_args = apply_filters('wpm_bmc_paypal_payment_args', $paypal_args);

        if (!$payment_item && $paypal_args['cmd'] == '_cart') {
            return;
        }

        $supportersModel->updateData($entryId, array(
            'payment_mode' => $paypalSettings['payment_mode']
        ));

        if ($transactionId) {
            $transactionModel->updateData($transactionId, array(
                'payment_mode' => $paypalSettings['payment_mode']
            ));
        }

        $paypal_redirect .= http_build_query($paypal_args);

        wp_send_json_success(array(
            'message' => __('You are redirected for payment.', 'buy-me-coffee'),
            'id' => $entryId,
            'redirectTo' => $paypal_redirect,
            'messageToShow' => __('Your are redirecting to paypal now', 'buy-me-coffee')
        ), 200);
        exit;
    }

    public function cartItems($item)
    {
        $paypal_args = array(
            'item_name_1' => 'coffee_payment',
            'quantity_1' => 1,
            'amount_1' => round($item->payment_total / 100, 2)
        );

        return $paypal_args;
    }

    public function successUrl($supporter)
    {
        return add_query_arg(array(
            'share_coffee' => '',
            'wpm_bmc_success' => 1,
            'hash' => $supporter->entry_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function failedUrl($supporter)
    {
        return add_query_arg(array(
            'wpm_bmc_failed' => 1,
            'share_coffee' => '',
            'wpm_bmc_submission' => $supporter->entry_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function updateStatus($data, $payment_id)
    {
        if ($data['txn_type'] != 'web_accept' && $data['txn_type'] != 'cart' && $data['payment_status'] != 'Refunded') {
            return;
        }

        if (empty($payment_id)) {
            return;
        }

        $transaction = (new Transactions())->find($payment_id);

        if (defined('PAYFORM_PAYPAL_IPN_DEBUG')) {
            error_log('IPN For Transaction: ' . json_encode($transaction));
        }

        if (!$transaction) {
            return;
        }

        if ($transaction->payment_method != 'paypal') {
            return;
        }

        $currency_code = strtolower($data['mc_currency']);

        if ($currency_code != strtolower($transaction->currency)) {
            $this->changeStatus('failed', $transaction);
            return;
        }

        $payment_status = strtolower($data['payment_status']);

        $paypal_amount = $data['mc_gross'];
        $isMismatchAmount = false;
        if (number_format((float)($transaction->payment_total / 100), 2) - number_format((float)$paypal_amount, 2) > 1) {
            $isMismatchAmount = true;
        }

        if ($isMismatchAmount) {
            $this->changeStatus('failed', $transaction);
            return;
        }

        if ('completed' == $payment_status || $transaction->payment_mode == 'test') {
            $this->changeStatus('paid', $transaction, $data);
            return;
        }

        if ('pending' == $payment_status && isset($data['pending_reason'])) {
            $this->changeStatus('processing', $transaction, $data);
        }
    }

    public function changeStatus($status, $transaction = null,  $data = array())
    {
        $supportersModel = new Supporters();
        $transactionModel = new Transactions();

        $updateData = array(
            'payment_status' => $status,
            'updated_at' => current_time('mysql')
        );

        $supportersModel->updateData($transaction->entry_id, $updateData);

        if (!empty($data) && isset($data['txn_id'])) {
            $updateData = [
                'status' => $status,
                'updated_at' => current_time('mysql'),
                'payment_note' => json_encode($data),
                'charge_id' => sanitize_text_field($data['txn_id'])
            ];
        }

        $transactionModel->updateData($transaction->id, $updateData);

    }

    public function maybeLoadModalScript()
    {
        $settings = $this->getSettings();
        if ($settings['payment_type'] == 'standard') {
            return;
        };

        $mode = $settings['payment_mode'];
        $clientId = $mode === 'test' ? $settings['test_public_key'] : $settings['live_public_key'];

        //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
        wp_enqueue_script('wpm-buymecoffee-checkout-sdk-' . $this->method, 'https://www.paypal.com/sdk/js?client-id=' . $clientId, [], null, false);

        Vite::enqueueScript('wpm-buymecoffee-checkout-handler-' . $this->method, 'js/PaymentMethods/paypal-checkout.js', ['wpm-buymecoffee-checkout-sdk-paypal', 'jquery'], '1.0.1', false);

    }

    public function render($template)
    {
        $this->maybeLoadModalScript();
        $id = $this->uniqueId('paypal_card');

        ?>
        <label class="wpm_paypal_card_label" for="<?php echo $id; ?>">
            <img width="60px" src="<?php echo esc_url(Vite::staticPath() . 'images/PayPal.svg'); ?>" alt="">
            <input
                    style="outline: none;"
                    type="radio" name="wpm_payment_method" class="wpm_paypal_card" id="<?php echo $id; ?>"
                    value="paypal">
<!--            <span class="payment_method_name">-->
<!--                                PayPal-->
<!--                            </span>-->
        </label>
        <?php
    }


    public function updatePayment()
    {
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $request = $_REQUEST;
        $chargeId = sanitize_text_field(ArrayHelper::get($request, 'charge_id'));
        $hash = sanitize_text_field(ArrayHelper::get($request, 'hash'));

        if ($chargeId == '' || $hash == '') {
            wp_send_json_error(array(
                'message' => __('Invalid request', 'buy-me-coffee'),
            ), 400);
        }

        $transaction = array(
            'charge_id' => $chargeId,
            'status' => 'paid-initially',
            'updated_at' => current_time('mysql'),
        );

        $transactionId = wpmBmcDB()->table('wpm_bmc_transactions')
            ->where('entry_hash', $hash)
            ->update($transaction);

        wpmBmcDB()->table('wpm_bmc_supporters')
            ->where('entry_hash', $hash)
            ->update(['payment_status' => 'paid-initially']);

        wp_send_json_success(array(
            'message' => __('Payment updated successfully', 'buy-me-coffee'),
            'data' => $transactionId
        ), 200);
    }

    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        $settings = $this->getSettings();
        return $settings['enable'] === 'yes';
    }
}
