class BmcFormHandler {
    constructor(form, config) {
        this.form = form;
        this.selector = '.wpm_bmc_form';
        this.customQuantity = false;
        this.config = config;
        this.paymentMethod = '';
        this.generalConfig = window.wpm_bmc_general;
        this.$formNoticeWrapper = form.parent().find('.wpm_bmc_form_notices');
    }

    $t(stringKey) {
        if (this.generalConfig.i18n[stringKey]) {
            return this.generalConfig.i18n[stringKey];
        }
        return '';
    }

    initForm() {
        // Hooks To get third party payment handler
        this.form.trigger('wpm_bmc_single', [this, this.config]);

        // Init Calculate Payments and on change re-calculate
        this.calculatePayments();
        this.form.find('.wpm_bmc_payment, .bmc_coffee input[type="radio"]').on('change', (e) => {
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

        // Payment Method set and handle change event
        this.maybeSelectFirstPaymentMethod();
        this.setSelectedMethod();
        this.form.find('.wpm_bmc_pay_method input').on('change', (e) => {
            this.setSelectedMethod();
            //class add for active methods label
            this.form.find('.wpm_bmc_pay_methods label').removeClass('wpm_payment_active');
            jQuery(e.target).parent().addClass('wpm_payment_active');
        });

        this.form.on('submit', (e) => {
            e.preventDefault();
            this.handleFormSubmit(this.form);
        });
    }


    handleFormSubmit(form) {
        form.find('button.wpm_submit_button').attr('disabled', true);
        form.addClass('wpm_submitting_form');
        form.parent().find('.wpm_form_notices').hide();

        jQuery.post(window.wpm_bmc_general.ajax_url, {
            action: 'wpm_bmc_submit',
            payment_total: form.data('wpm_payment_total'),
            coffee_count: form.data('coffee_count'),
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
                form: this.form, response: response
            }
        }));
    }

    maybeSelectFirstPaymentMethod() {
        let hasFirstMethod = this.form.find("input:radio[name='wpm_payment_method']");
        if (hasFirstMethod.length === 1) {
            hasFirstMethod.closest('.wpm_bmc_pay_methods')?.hide();
        }
        if (hasFirstMethod.first().length) {
            hasFirstMethod.first().attr('checked', true);
        } else {
            this.form.find('button.wpm_submit_button').css('cursor', 'not-allowed').attr('disabled', true);
        }
    }

    setSelectedMethod() {
        let paymentMethod = this.form.find('.wpm_bmc_pay_method input:checked');
        this.form.data('wpm_selected_payment_method', paymentMethod.val());
        paymentMethod.parent().addClass('wpm_payment_active');
    }

    toggleCustomQuantity(val) {
        this.customQuantity = val;
        const customQuantityInput = this.form.find('.wpm_bmc_custom_quantity');
        let quantity;
        if (this.customQuantity) {
            customQuantityInput.addClass('custom_quantity_active');
            quantity = this.form.find('.wpm_bmc_custom_quantity').val();
        } else {
            quantity = this.form.find('.bmc_coffee input[type="radio"]:checked')?.val();
            customQuantityInput.removeClass('custom_quantity_active');
        }
        this.form.data('coffee_count', quantity ? quantity : 1);

        this.calculatePayments();
    }

    calculatePayments() {
        let amount = this.form.find('.wpm_bmc_payment').val();
        let quantity = this.form.data('coffee_count') ? this.form.data('coffee_count') : 1;
        let amountCents = parseInt(parseFloat(amount) * 100 * quantity);
        this.form.data('wpm_payment_total', amountCents);
        //set total
        this.form.find('.wpm_submit_button .wpm_payment_total_amount').html(parseInt(amount * quantity));
    }

}

export default BmcFormHandler;