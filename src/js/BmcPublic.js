import BmcFormHandler from './BmcFormHandler.js';

var buyMeCoffeeApp = {};

(function ($) {
    buyMeCoffeeApp = {
        forms: {},
        general: window.wpm_buymecoffee_general,
        formData: {},
        init() {
            this.forms = $('.wpm_buymecoffee_form');
            $.each(this.forms, function ($form) {
                let form = $(this);
                // let formInstance = form.attr('wpm_buymecoffee_form');
                let formSettings = window['wpm_buymecoffee_settings'];
                let formHandler = new BmcFormHandler(form, formSettings);
                formHandler.initForm();
            });

            this.initOther();
        },
        initOther() {
            $('.wpm_buymecoffee_form input').on('keypress', function (e) {
                return e.which !== 13;
            });
            let $inputs = $('.wpm_buymecoffee_form').find('input[data-required="yes"][data-type="input"],textarea[data-required="yes"],select[data-required="yes"]');
            $inputs.on('keypress blur', function (e) {
                if ($(this).val()) {
                    $(this).removeClass('wpf_has_error');
                }
            });
        }
    };

    $(window).on('load', function () {
        buyMeCoffeeApp.init();
    });

}(jQuery));