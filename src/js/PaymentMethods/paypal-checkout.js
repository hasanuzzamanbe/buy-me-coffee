class PaypalCheckout {
    constructor($form, $response) {
        this.form = $form
        this.data = $response.data
    }

    init() {
        this.form.find('.wpm_submit_button, .wpm_bmc_pay_method').hide()

        let paypalButtonContainer = jQuery("<div style='padding: 12px;'></div>")
        paypal
            .Buttons({
                style: {
                    shape: 'pill',
                    layout: 'vertical',
                    label: 'paypal',
                    // tagline: 'false',
                    size: 'responsive',
                    disableMaxWidth: true
                },
                createOrder: (data, actions) => {
                    return actions.order.create({
                        purchase_units: [this.data.purchase_units]
                    })
                },
                onApprove: (data, actions) => {
                    return actions.order.capture().then((details) => {
                        console.log(this.data?.confirmation_url)
                        window.location = this.data?.confirmation_url;
                    })
                },
                onError: function (err) {
                    alert('An error occurred: ' + err)
                }
            })
            .render(paypalButtonContainer[0])

        this.form.find('.wpm_bmc_form_submit_wrapper').hide();
        this.form.find('.wpm_bmc_pay_methods')?.parent().append(paypalButtonContainer);
    }
}

window.addEventListener('wpm_bmc_payment_next_action_paypal', function (e) {
    new PaypalCheckout(e.detail.form, e.detail.response).init()
})