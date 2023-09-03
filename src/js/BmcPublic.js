import BmcFormHandler from './BmcFormHandler.js';

let buyMeCoffeeApp = {};

(function ($) {
    buyMeCoffeeApp = {
        forms: {},
        general: window.wpm_buymecoffee_general,
        formData: {},
        initiated: false,
        init() {
            this.forms = $('.wpm_buymecoffee_form');
            console.log('initiating', this.forms)
            $.each(this.forms, function ($form) {
                let form = $(this);
                // let formInstance = form.attr('wpm_buymecoffee_form');
                let formSettings = window['wpm_buymecoffee_settings'];
                let formHandler = new BmcFormHandler(form, formSettings);
                formHandler.initForm();
            });

            this.initOther();
            this.initiated = true;
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
        console.log('window loaded', buyMeCoffeeApp.initiated)
        if (!buyMeCoffeeApp?.initiated) {
            console.log('initiating');
            buyMeCoffeeApp.init();
        }
    });

    $(document).ready(function() {
        let modal = $('#bmc_modal_wrapper');
        let btn = $('#bmc_open_modal_btn');
        let span = $('.close');
        btn.click(function() {
            modal.show();
        });
        span.click(function() {
            modal.hide();
        });
        // When the templates clicks anywhere outside the modal, close it
        $(window).click(function(event) {
            if (event.target == modal[0]) {
                modal.hide();
            }
        });
    });
      

}(jQuery));