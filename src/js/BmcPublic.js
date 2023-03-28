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

    $(document).ready(function() {
        // Get the modal
        var modal = $('#bmc_modal_wrapper');
        // Get the button that opens the modal
        var btn = $('#bmc_open_modal_btn');
        // Get the <span> element that closes the modal
        var span = $('.close');
        // When the user clicks on the button, open the modal
        btn.click(function() {
            modal.show();
        });
        // When the user clicks on <span> (x), close the modal
        span.click(function() {
            modal.hide();
        });
        // When the user clicks anywhere outside of the modal, close it
        $(window).click(function(event) {
            if (event.target == modal[0]) {
                modal.hide();
            }
        });
    });
      

}(jQuery));