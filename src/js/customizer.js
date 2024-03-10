jQuery(document).ready(function($) {
    let unsavedChanges = false;
    // Variables
    const colorButton = $(".tab__content .colors li");
    colorButton.each(function() {
        const color = $(this).attr('data-color');
        $(this).css('background-color', color);
    });

    let buttonColor = null;
    let borderColor = null;
    let bgColor = null;

    colorButton.on("click", function () {
        $(".colors > li").removeClass("active-color");
        $(this).addClass("active-color");

        buttonColor = $(this).attr("data-color");
        borderColor = rgbToRgba(buttonColor, '25%');
        bgColor = rgbToRgba(buttonColor, '5%');

         let groupStyle ={
            "background-color" : bgColor,
            'border-color': borderColor
        };

        $("#buymecoffee_preview_top .buymecoffee_profile_hr").css("background-color", borderColor);
        $("#buymecoffee_preview_top .buymecoffee_payment_item .buymecoffee_payment_input_content").css(groupStyle);
        $("#buymecoffee_preview_top button#wpm_submit_button")
            .attr('style', $(this).attr('style') + 'background-color:' + buttonColor + '!important;');
    });



    $('#buymecoffee_save_changes').on("click", function () {
        $.post(window.buymecoffee_general.ajax_url, {
            action: 'buymecoffee_admin_ajax',
            route: 'save_form_design',
            buymecoffee_nonce: window.buymecoffee_general.buymecoffee_nonce,
            data: {
                button_style: buttonColor,
                bg_style: bgColor,
                border_style: borderColor,
            }
        })
            .then(res => {
                hideSettings();
            }).catch (error => {
            alert(error?.responseJSON?.data?.message);
        });
    });

    $('.controller').on("click", function () {
        $('.wrapper').toggle();
    });

    function rgbToRgba(rgb, alpha) {
        var values = rgb.substr(4, rgb.length - 5).split(", ");
        return "rgba(" + values[0] + ", " + values[1] + ", " + values[2] + ", " + alpha + ")";
    }

    function hideSettings() {
        $('.wrapper').hide();
    }
});