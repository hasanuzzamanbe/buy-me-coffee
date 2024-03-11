jQuery(document).ready(function($) {
    const colorButton = $(".buymecoffee_color_wrapper .buymecoffee_colors li");

    let visibleCustomizer = false;
    let buttonColor = null;
    let borderColor = null;
    let bgColor = null;
    let quote = null;

    initColors();
    customizerMenuRegister();
    quotesEditRegister();
    saveActionRegister();
    colorSwitcherRegister();

    function initColors() {
        colorButton.each(function() {
            const color = $(this).attr('data-color');
            $(this).css('background-color', color);
        });
    }
    function colorSwitcherRegister() {
        colorButton.on("click", function () {
            $(".buymecoffee_colors > li").removeClass("active-color");
            $(this).addClass("active-color");

            buttonColor = $(this).attr("data-color");
            borderColor = rgbToRgba(buttonColor, '25%');
            bgColor = rgbToRgba(buttonColor, '5%');

            let groupStyle ={
                "background-color" : bgColor,
                'border-color': borderColor
            };

            $(".buymecoffee_profile_hr").css("background-color", borderColor);
            $(".buymecoffee_payment_item .buymecoffee_payment_input_content").css(groupStyle);
            $(".buymecoffee_currency_prefix").css("background-color", buttonColor);
            $("button#wpm_submit_button")
                .attr('style', $(this).attr('style') + 'background-color:' + buttonColor + '!important;');
        });
    }

    function saveActionRegister() {
        $('#buymecoffee_save_changes').on("click", function () {
            $.post(window.buymecoffee_general.ajax_url, {
                action: 'buymecoffee_admin_ajax',
                route: 'save_form_design',
                buymecoffee_nonce: window.buymecoffee_general.buymecoffee_nonce,
                data: {
                    button_style: buttonColor,
                    bg_style: bgColor,
                    border_style: borderColor,
                    quote: quote
                }
            })
                .then(res => {
                    hideSettings();
                }).catch (error => {
                alert(error?.responseJSON?.data?.message);
            });
        });

    }

    function customizerMenuRegister() {
        $('.buymecoffee_customizer_menu').on("click", function () {
            $('.buymecoffee_customizer_wrapper, .buymecoffee_edit_action_wrapper').toggle();
            if (visibleCustomizer) {
                $('.buymecoffee_main_quote').show();
                $('.buymecoffee_edit_action_wrapper blockquote').hide();
            } else {
                $('.buymecoffee_main_quote').hide();
                $('.buymecoffee_edit_action_wrapper blockquote').show();
            }
            visibleCustomizer = !visibleCustomizer;
        });
    }

    function quotesEditRegister()
    {
        $('.buymecoffee_edit_action').on("click", function () {
            $('.buymecoffee_edit_action_wrapper blockquote').toggle();
            $('.buymecoffee_main_quote').toggle();
        })

        $('.buymecoffee_edit_action_wrapper input').on('input', function() {
            $('.buymecoffee_main_quote p')[0]
                .innerHTML = quote = $(this).val();
        });
    }

    function rgbToRgba(rgb, alpha) {
        let values = rgb.substr(4, rgb.length - 5).split(", ");
        return "rgba(" + values[0] + ", " + values[1] + ", " + values[2] + ", " + alpha + ")";
    }

    function hideSettings() {
        visibleCustomizer = false;
        $('.buymecoffee_customizer_wrapper, .buymecoffee_edit_action_wrapper').hide();
        $('.buymecoffee_main_quote').show();
    }
});