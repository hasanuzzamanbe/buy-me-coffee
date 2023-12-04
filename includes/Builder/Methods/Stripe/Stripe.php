<?php

namespace BuyMeCoffee\Builder\Methods\Stripe;

use BuyMeCoffee\Builder\Methods\BaseMethods;
use BuyMeCoffee\Classes\Vite;
use BuyMeCoffee\Helpers\PaymentHelper;
use BuyMeCoffee\Models\Supporters;
use BuyMeCoffee\Models\Transactions;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Stripe extends BaseMethods
{
    public function __construct()
    {
        parent::__construct(
            'Stripe',
            'stripe',
            'Stripe is a payment gateway that allows you to accept payments from your customers.',
            Vite::staticPath() . 'images/stripe.svg'
        );

        add_action('wpm_bmc_make_payment_stripe', array($this, 'makePayment'), 10, 3);
        add_action("wpm_bmc_ipn_endpoint_stripe", array($this, 'verifyIpn'), 10, 2);
    }

    public function makePayment($transactionId, $entryId, $form_data)
    {
        $transactionModel = new Transactions();
        $transaction = $transactionModel->find($transactionId);

        $supportersModel = new Supporters();
        $supporter = $supportersModel->find($entryId);
        $hash = $transaction->entry_hash;

        $keys = StripeSettings::getKeys();
        $apiKey = $keys['secret'];

        $paymentArgs = array(
            'supporter_id' => $supporter->id,
            'client_reference_id' => $hash,
            'amount' => (int) round($transaction->payment_total, 0),
            'currency' => strtolower($transaction->currency),
            'description' => "Buy coffee from {$supporter->supporters_name}",
            'supporters_email' => $supporter->supporters_email,
            'supporters_name' => $supporter->supporters_name,
            'success_url' => $this->successUrl($supporter),
            'public_key' => $keys['public']
        );

        $this->handleInlinePayment($transaction, $paymentArgs, $apiKey);
    }

    public function paymentConfirmation()
    {
        if (!isset($_REQUEST['intentId'])) {
            return;
        }
        $intentId = sanitize_text_field($_REQUEST['intentId']);
        (new PaymentHelper())->updatePaymentData($intentId);
    }

    public function handleInlinePayment($transaction, $paymentArgs, $apiKey)
    {
        try {
            if (!empty($paymentArgs['supporters_email'])) {
                $customer = (new API())->makeRequest('customers', [
                        'name' => $paymentArgs['supporters_name'],
                        'email' => $paymentArgs['supporters_email'],
                ],$apiKey, 'POST');
                if (!is_wp_error($customer) && isset($customer['id'])) {
                    $paymentArgs['vendor_customer_id'] = $customer['id'];
                }
            }

            $intentData = $this->intentData($paymentArgs);
            $invoiceResponse = (new API())->makeRequest('payment_intents', $intentData, $apiKey, 'POST');

            $transaction->payment_args = $paymentArgs;

            $responseData = [
                'nextAction' => 'stripe',
                'actionName' => 'custom',
                'buttonState' => 'hide',
                'intent' => $invoiceResponse,
                'order_items' => $transaction,
                'message_to_show' => __('Payment Modal is opening, Please complete the payment', 'buy-me-coffee'),
            ];

            wp_send_json_success($responseData, 200);
        } catch (\Exception $e) {
            wp_send_json_error([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 423);
        }
    }

    public function intentData($args)
    {
        $sessionPayload = array(
            'amount' => $args['amount'],
            'currency' => $args['currency'],
            'metadata' => [
                'ref_id'  => $args['client_reference_id'],
                'supporter' => admin_url('admin.php?page=buy-me-coffee.php#/supporter/' . $args['supporter_id'])
            ],
        );

        if (isset($args['vendor_customer_id'])) {
            $sessionPayload['customer'] = $args['vendor_customer_id'];
        }

        return $sessionPayload;
    }
    public function successUrl($supporter)
    {
        return add_query_arg(array(
            'share_coffee' => '',
            'wpm_bmc_success' => 1,
            'hash' => $supporter->entry_hash,
            'payment_method' => 'stripe'
        ), home_url());
    }

    public function verifyIpn()
    {
        $data = (new IPN())->IPNData();

        error_log(print_r($data));
        error_log("data event");

        if (!$data) {
            error_log('invalid data');
            return;
        }

        $eventId = $data->id;
        $invoice = (new API())->getInvoice($eventId);

        $orderHash = $this->getOrderHash($invoice);

        if (!$invoice || is_wp_error($invoice)) {
            error_log('invoice not found');
            return;
        }

        $status = $invoice->data->object->status;

        if ($status === 'succeeded') {
            $status = 'paid';
        }

        $this->updateStatus($orderHash, $status);
    }

    public function updateStatus($orderHash, $status)
    {
        $transactions = new Transactions();
        $transaction = $transactions->find($orderHash, 'entry_hash');

        $supportersModel = new Supporters();
        $supportersModel->updateData($transaction->entry_id, ['payment_status' => $status]);

        $transactions->updateData($transaction->id, ['payment_status' => $status]);

        do_action('wpm_bmc_payment_status_updated', $transaction->id, $status);
    }

    public static function getOrderHash($event)
    {
        $eventType = $event->type;

        $metaDataEvents = [
            'checkout.session.completed',
            'charge.refunded',
            'charge.succeeded',
            'invoice.paid'
        ];

        if (in_array($eventType, $metaDataEvents)) {
            $data = $event->data->object;
            $metaData = (array)$data->metadata;
            return Arr::get($metaData, 'ref_id');
        }

        return false;
    }

    public function sanitize($settings)
    {
        foreach ($settings as $key => $value) {
            $settings[$key] = sanitize_text_field($value);
        }
        return $settings;
    }

    public function getPaymentSettings()
    {
        wp_send_json_success(array(
            'settings' => $this->getSettings(),
            'webhook_url' => site_url() . '?wpm_bmc_stripe_listener=1'
        ), 200);
    }

    public function getSettings()
    {
        $settings = get_option('wpm_bmc_payment_settings_' . $this->method, []);

        $defaults = array(
            'enable' => 'no',
            'payment_mode' => 'test',
            'live_pub_key' => '',
            'live_secret_key' => '',
            'test_pub_key' => '',
            'test_secret_key' => ''
        );
        return wp_parse_args($settings, $defaults);
    }

    public function maybeLoadModalScript()
    {
        //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
        wp_enqueue_script('wpm-buymecoffee-checkout-sdk-' . $this->method, 'https://js.stripe.com/v3/',null, false);
        Vite::enqueueScript('wpm-buymecoffee-checkout-handler-' . $this->method, 'js/PaymentMethods/stripe-checkout.js', ['wpm-buymecoffee-checkout-sdk-stripe', 'jquery'], '1.0.1', false);
    }

    public function render($template)
    {
        $this->maybeLoadModalScript();
        $id = $this->uniqueId('stripe_card');
        ?>
        <label class="wpm_stripe_card_label" for="<?php echo esc_attr($id); ?>">
            <img width="50px" src="<?php echo esc_url(Vite::staticPath() . 'images/stripe.svg'); ?>" alt="">
            <input
                    style="outline: none;"
                    type="radio" class="wpm_stripe_card" name="wpm_payment_method" id="<?php echo esc_attr($id); ?>"
                    value="stripe">
<!--                            <span class="payment_method_name">-->
<!--                                Stripe-->
<!--                            </span>-->
        </label>
        <?php
    }

    public function isEnabled()
    {
        // TODO: Implement isEnabled() method.
        $settings = $this->getSettings();
        return $settings['enable'] === 'yes';
    }
}