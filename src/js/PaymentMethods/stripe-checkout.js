class StripeCheckout {
    constructor ($form, $response) {
        window.myform = $form;
        this.form = $form;
        this.data = $response.data;
        this.intent = $response.data?.intent;
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

        this.form.find('.wpm_bmc_pay_methods')?.parent().prepend("<div class='wpm_bmc_payment_processor'><p class='wpm_bmc_loading_processor' style='color: #48a891;font-size: 14px;'>Payment processing...<p/></div>");
        const formSelector = '#' + this.form.attr('id') + ' .wpm_bmc_payment_processor';
        paymentElement.mount(formSelector);

        let that= this;
        paymentElement.on('ready', (event) => {
            this.form.find('.wpm_bmc_loading_processor').remove();
            this.form.find('.wpm_bmc_payment_processor').append(submitButton);
            this.form.find('.wpm_bmc_input_content, .wpm_bmc_pay_methods, .wpm_bmc_payment_item').hide();
            this.form.prepend("<p class='complete_payment_instruction'>Please complete your donation with Stripe 👇</p>");

            this.form.find('#wpm_bmc_pay_now').on('click', function(e) {
                e.preventDefault()
                jQuery(this).text('Processing...');
                elements.submit().then(result=> {
                    stripe.confirmPayment({
                        elements,
                        confirmParams: {
                        },
                        redirect: 'if_required'
                    }).then((result) => {
                        if (result?.paymentIntent?.id) {
                            jQuery.post(window.wpm_bmc_general.ajax_url, {
                                action: 'wpm_bmc_payment_confirmation_stripe',
                                intentId: result?.paymentIntent?.id,
                            }).then((response) => {
                                window.location.href = that.data?.order_items?.payment_args?.success_url;
                            });
                        }
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