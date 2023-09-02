class BmcFormHandler {
    constructor(form, config) {
        this.form = form;
        this.selector = '.wpm_buymecoffee_form';
        this.customQuantity = false;
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
        this.form.find('.wpm_buymecoffee_payment, .bmc_coffee input[type="radio"]').on('change', (e) => {
            this.calculatePayments();
        });

        // on change select radio button quantity
        this.form.find('.bmc_coffee input[type="radio"]').on('change', (e) => {
            this.toggleCustomQuantity(false);
        });

        // on change custom quantity
        this.form.find('.wpm_bmc_custom_quantity').on('change', (e) => {
            this.form.find('.bmc_coffee input[type="radio"]').prop('checked', false);
            this.toggleCustomQuantity(true);
        });

        this.selectedMethod();
        this.form.find('.wpm_bmc_pay_method input').on('change', (e) => {
            this.selectedMethod();

            //class add for active methods label
            this.form.find('.wpm_bmc_pay_methods label').removeClass('wpm_payment_active');
            jQuery(e.target).parent().addClass('wpm_payment_active');
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
                payment_method: form.data('wpm_selected_payment_method'),
                currency: form.data('wpm_currency'),
                form_data: jQuery(form).serialize()
            })
                .then(response => {
                    if (response.data?.redirectTo) {
                        window.location.href = response.data.redirectTo;
                    }
                    if (response.data?.actionName == 'custom') {
                        this.fireCustomEvent(response.data.nextAction, response);
                        return;
                    }
                });
    }

    fireCustomEvent(eventName, response) {
        window.dispatchEvent(new CustomEvent('wpm_bmc_payment_next_action_' + response?.data?.nextAction, {
            detail: {
                form: this.form,
                response: response
            }
        }));
    }

    selectedMethod() {
        let paymentMethod = this.form.find('.wpm_bmc_pay_method input:checked');
        this.form.data('wpm_selected_payment_method', paymentMethod.val());
        paymentMethod.parent().addClass('wpm_payment_active');
    }

    toggleCustomQuantity(val) {
        this.customQuantity = val;
        const customQuantityInput = this.form.find('.wpm_bmc_custom_quantity');
        this.customQuantity ? customQuantityInput.addClass('custom_quantity_active') : customQuantityInput.removeClass('custom_quantity_active');
        this.calculatePayments();
    }

    calculatePayments() {
        let amount = this.form.find('.wpm_buymecoffee_payment').first().val();

        //quantity
        let quantity = 1;
        quantity = this.form.find('.bmc_coffee input[type="radio"]:checked')?.val();
        if (this.customQuantity) {
            quantity = this.form.find('.wpm_bmc_custom_quantity').val();
        }

        let amountCents = parseInt(parseFloat(amount) * 100 * quantity);
        this.form.data('wpm_payment_total', amountCents);
        //set total
        this.form.find('#wpm_submit_button .wpm_payment_total_amount').html(parseInt(amount * quantity));
    }

}

export default BmcFormHandler;