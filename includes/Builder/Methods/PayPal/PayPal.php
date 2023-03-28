<?php
namespace buyMeCoffee\Builder\Methods\PayPal;

use buyMeCoffee\Models\Supporters;
use buyMeCoffee\Models\Transactions;
use buyMeCoffee\Builder\Methods\BaseMethods;

 class PayPal extends BaseMethods
{
    public function __construct()
    {
        parent::__construct(
            'PayPal',
            'paypal',
            'PayPal is the faster, safer way to send money, make an online payment, receive money or set up a merchant account.',
            BUYMECOFFEE_URL. 'assets/images/paypal.png'
        );
        add_action( 'wpm_buymecoffee_make_payment_paypal' , array( $this , 'makePayment' ) , 10 , 3);
        add_action('init', array($this, 'ipnVerification'));
        add_action('wpm_bmc_paypal_action_web_accept', array($this, 'updateStatus'), 10, 2);
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

    public function getPaymentSettings()
    {
        wp_send_json_success(array(
            'settings'       => $this->getSettings(),
            'webhook_url'    => site_url() . '?wpm_bmc_paypal_listener=1'
        ), 200);
    }

    public function getSettings()
    {
        $settings = get_option('wpm_bmc_payment_settings_' . $this->method, array());
        
        $defaults = array(
            'enable' => 'no',
            'payment_mode'    => 'test',
            'paypal_email'    => '',
            'disable_ipn_verification' => 'no',
        );

        return  wp_parse_args($settings, $defaults);
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
            'item_name_1' => 'coffee_payment',
            'quantity_1' => 1,
            'amount_1' => round($item->payment_total / 100, 2)
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

    public function ipnVerification()
    {
        if (isset($_REQUEST['wpm_bmc__paypal_ipn'])) {
            (new IPN())->ipnVerificationProcess();
        }
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
        $business_email = isset($data['business']) && is_email($data['business']) ? trim($data['business']) : trim($data['receiver_email']);

        $paypalSettings = $this->getSettings();

        if (strcasecmp($business_email, trim($paypalSettings['paypal_email'])) != 0) {
            $this->changeStatus('failed');
            return;
        }

        $currency_code = strtolower($data['mc_currency']);

        if ($currency_code != strtolower($transaction->currency)) {
            $this->changeStatus('failed');
            return;
        }

        $payment_status = strtolower($data['payment_status']);

        $paypal_amount = $data['mc_gross'];
        $isMismatchAmount = false;
        if (number_format((float)($transaction->payment_total / 100), 2) - number_format((float)$paypal_amount, 2) > 1) {
            $isMismatchAmount = true;
        }
        if ($isMismatchAmount) {
            $this->changeStatus('failed');
            return;
        }

        if ($data['custom'] != $transaction->id) {
            $this->changeStatus('failed');
            return;
        }

        if ('completed' == $payment_status || $transaction->payment_mode == 'test') {
            $this->changeStatus('paid', $data, $transaction);
            return;
        }

        if ('pending' == $payment_status && isset($data['pending_reason'])) {
            $this->changeStatus('processing', $data, $transaction);
        }
    }

    public function changeStatus($status, $data = array(), $transaction = null)
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
                $updateData['charge_id'] = $data['txn_id']
            ];
        }

        $transactionModel->updateData($transaction->id, $updateData);
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
