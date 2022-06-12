<?php
namespace buyMeCoffee\Builder\Methods;

use buyMeCoffee\Models\Supporters;
use buyMeCoffee\Models\Transactions;
use buyMeCoffee\Helpers\ArrayHelper;

 class PayPal extends BaseMethods
{
    public function __construct()
    {
        parent::__construct('paypal');
        add_action( 'wpm_buymecoffee_make_payment_paypal' , array( $this , 'makePayment' ) , 10 , 3);
    }

    public function getSettings()
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

    public function makePayment($transactionId, $entryId, $form_data)
    {
        $paypalSettings = $this->getSettings();
        $paypal_redirect = 'https://www.sandbox.paypal.com/cgi-bin/webscr/?';

        if ($paypalSettings['payment_mode'] === 'live') {
            $paypal_redirect = 'https://www.paypal.com/cgi-bin/webscr/?';
        }

        $supportersModel = new Supporters();
        $supporter = $supportersModel->find($entryId);
        $listener_url = apply_filters('wpm_bmc_paypal_ipn_url', site_url('?wpm_bmc__paypal_ipn=1'), $supporter);

        $paypal_args = array(
            'cmd'           => '_cart',
            'upload'        => '1',
            'business'      => $paypalSettings['paypal_email'],
            'email'         => $supporter->supporters_email,
            'no_shipping'   => '1',
            'no_note'       => '1',
            'currency_code' => $supporter->currency ? $supporter->currency : 'USD',
            'charset'       => 'UTF-8',
            'custom'        => $transactionId,
            'return'        => $this->successUrl($supporter),
            'notify_url'    => $listener_url,
            'cancel_return' => $this->failedUrl($supporter),
            'image_url'     => '',
        );

        $transactionModel = new Transactions();
        $transaction = $transactionModel->find($transactionId);
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
                'message' => __('You are redirected for payment.', 'buymecoffee'),
                'id' => $entryId,
                'redirectTo' => $paypal_redirect,
                'messageToShow' => __('Your are redirecting to paypal now', 'wppayform')
        ), 200);
        exit;
    }

    public function cartItems($item)
    {
        $paypal_args = array(
            'wpm_coffee_payment' => '_coffee_payment',
            'wpm_coffee_quantity' => 1,
            'wpm_coffee_amount' => round($item->payment_total / 100, 2)
        );
        return $paypal_args;
    }

    public function successUrl($supporter)
    {
        return add_query_arg(array(
            'wpm_bmc__paypal_success' => 1,
            'wpm_bmc_submission' => $supporter->entry_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function failedUrl($supporter)
    {
        return add_query_arg(array(
            'wpm_bmc__paypal_failed' => 1,
            'wpm_bmc_submission' => $supporter->entry_hash,
            'payment_method' => 'paypal'
        ), home_url());
    }

    public function render($template)
    {
        ?>
            <label class="wpm_paypal_card_label" for="wpm_paypal_card">
                <img width="50px" src="<?php echo BUYMECOFFEE_URL . 'assets/images/paypal.png'; ?>" alt="">
                <input
                    style="outline: none;"
                    type="radio" name="wpm_payment_method" class="wpm_paypal_card" id="wpm_paypal_card"
                    value="paypal">
                <span style="font-size:14px;">
                    <strong>PayPal</strong>
                </span>
            </label>
        <?php
    }
}
