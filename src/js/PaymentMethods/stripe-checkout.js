class StripeCheckout {
    constructor ($form, $response) {
        this.form = $form
        this.data = $response.data
        this.intent = $response.data?.intent
    }

    init () {
        this.form.find('.wpm_submit_button').hide();
        let amounPrefix = this.form.find('.wpm_payment_total_amount_prefix').text();

        let buttonText = "Pay " + amounPrefix + (parseInt(this.intent.amount) / 100) + " Now"

        let submitButton = "<button id='wpm_bmc_pay_now' style='margin-top: 20px;width: 100%;' type='submit'>" + buttonText + "</button>";
        var stripe = Stripe(this.data?.order_items?.payment_args?.public_key);
        const elements = stripe.elements({
            clientSecret: this.intent.client_secret
        });
        const paymentElement = elements.create('payment', {});
        paymentElement.mount('#wpm_bmc_pay_methods');

        this.form.find('.wpm_bmc_pay_methods')?.parent().append("<p class='wpm_bmc_loading_processor' style='color: #48a891;font-size: 14px;'>Payment processing...</p>");
        this.form.find('#fluent_cart_order_btn').hide();

        let that= this;
        paymentElement.on('ready', (event) => {
            jQuery('.wpm_bmc_loading_processor').remove();
            jQuery('#wpm_bmc_pay_methods').append(submitButton);
            this.form.find('.wpm_bmc_input_content, .wpm_bmc_payment_input_content').hide();
            this.form.prepend("<p class='complete_payment_instruction'>Please complete your donation with Stripe 👇</p>");

            jQuery('#wpm_bmc_pay_now').on('click', function(e) {
                e.preventDefault()
                jQuery(this).text('Processing...');
                elements.submit().then(result=> {
                    stripe.confirmPayment({
                        elements,
                        confirmParams: {
                            return_url: that.data?.order_items?.payment_args?.success_url
                        }
                    }).then((result) => {
                        jQuery(this).text(buttonText);
                    })
                }).catch(error => {
                    jQuery(this).text(buttonText);
                })
            })
        });
    }
  }
  
  window.addEventListener("wpm_bmc_payment_next_action_stripe", function (e) {
    new StripeCheckout(e.detail.form, e.detail.response).init();
  });