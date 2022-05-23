class BmcFormHandler {
    constructor(form, config) {
        console.log(form)
        this.form = form;
        this.selector = '.wpm_buymecoffee_form';
        this.config = config;
        this.paymentMethod = '';
        this.generalConfig = window.wp_payform_general;
        this.$formNoticeWrapper = form.parent().find('.wpm_buymecoffee_form_notices');
    }

    $t(stringKey) {
        if(this.generalConfig.i18n[stringKey]) {
            return this.generalConfig.i18n[stringKey];
        }
        return '';
    }

    initForm() {
        // Hooks To get third party payment handler
        this.form.trigger('wpm_buymecoffee_single', [this, this.config]);

        // Init Calculate Payments and on change re-calculate
        this.calculatePayments();
        this.form.find('.wpm_buymecoffee_payment').on('change', (e) => {
            this.calculatePayments();
        });

        // this.initPaymentMethodChange();

        //stripe_checkout
        // this.paymentMethod = 'stripe';
        // this.stripe = Stripe(this.config.stripe_pub_key);

        jQuery(document).on('submit', this.selector, (e) => {
            e.preventDefault();
            this.handleFormSubmit(this.form);
        });
    }

    handleFormSubmit(form) {
            form.find('button.wpm_submit_button').attr('disabled', true);
            form.addClass('wpm_submitting_form');
            form.parent().find('.wpm_form_notices').hide();

            jQuery.post(window.wpm_buymecoffee_general.ajax_url, {
                action: 'wpm_buymecoffee_submit',
                payment_total: form.data('wpm_payment_total'),
                form_data: jQuery(form).serialize()
            })
                .then(response => {
                    console.log(response)
                });
    }

    calculatePayments() {
        console.log('recalculating payments');
        let amount = this.form.find('.wpm_buymecoffee_payment').first().val();
        let amountCents = parseInt(parseFloat(amount) * 100);
        this.form.data('wpm_payment_total', amountCents);
    }

    initPaymentMethodChange() {
        let paymentMethod = this.form.find('[data-wpm_buymecoffee_payment_method]').data('wpm_buymecoffee_payment_method');
        if (paymentMethod) {
            this.form.data('selected_payment_method', paymentMethod);
        }
    }

    handlePaymentMethodChange(paymentMethod) {
        this.form.data('selected_payment_method', paymentMethod);
    }

}

export default BmcFormHandler;