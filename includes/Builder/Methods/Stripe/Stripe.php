<?php
namespace BuyMeCoffee\Builder\Methods\Stripe;

use BuyMeCoffee\Builder\Methods\BaseMethods;

class Stripe extends BaseMethods
{
    public function __construct()
    {
        parent::__construct(
            'Stripe',
            'stripe',
            'Stripe is a payment gateway that allows you to accept payments from your customers.',
            WPM_BMC_URL . 'assets/images/credit-card.png'
        );

        add_action('wpm_bmc_make_payment_stripe' , array( $this , 'makePayment' ) , 10 , 3);
    }

    public function makePayment($transactionId, $entryId, $form_data)
    {
       // TO-Do will implement later on upcoming version
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
            'settings'       => $this->getSettings(),
            'webhook_url'    => site_url() . '?wpm_bmc_stripe_listener=1'
        ), 200);
    }

    public function getSettings()
    {
        $settings = get_option('wpm_bmc_payment_settings_' . $this->method, []);

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

    public function render($template)
    {
        ?>
            <label class="wpm_stripe_card_label" for="wpm_stripe_card">
                <img width="50px" src="<?php echo WPM_BMC_URL . 'assets/images/credit-card.png'; ?>" alt="">
                <input
                    style="outline: none;"
                    type="radio" class="wpm_stripe_card" name="wpm_payment_method" id="wpm_stripe_card"
                    value="stripe">
<!--                <span class="payment_method_name">-->
<!--                    stripe-->
<!--                </span>-->
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