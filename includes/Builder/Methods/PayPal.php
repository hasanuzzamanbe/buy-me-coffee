<?php
namespace buyMeCoffee\Builder\Methods;

 class PayPal extends BaseMethods
{
    public function __construct()
    {
        parent::__construct('paypal');
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
