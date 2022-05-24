<?php
namespace buyMeCoffee\Builder\Methods;

class Stripe extends BaseMethods
{
    public function __construct()
    {
        parent::__construct('stripe');
       add_action('wpm_buymecoffee_make_payment_stripe' , array( $this , 'makePayment' ) , 10 , 3);
    }

    public function makePayment($transactionId, $entryId, $form_data)
    {
        var_dump('from stripe');
        die();
    }

    public function render($template)
    {
        ?>
            <label class="wpm_stripe_card_label" for="wpm_stripe_card">
                <img width="50px" src="<?php echo BUYMECOFFEE_URL . 'assets/images/credit-card.png'; ?>" alt="">
                <input
                    checked="checked"
                    style="outline: none;"
                    type="radio" class="wpm_stripe_card" name="wpm_payment_method" id="wpm_stripe_card"
                    value="stripe">
                <span style="font-size:14px;">
                    <strong>Stripe</strong>
                </span>
            </label>
        <?php
    }
}