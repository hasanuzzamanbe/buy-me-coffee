jQuery(document).ready(function($) {
    console.log('hello')
    // Variables
    const colorButton = $(".tab__content .colors li");
    colorButton.each(function() {
        const color = $(this).attr('data-color');
        $(this).css('background-color', color);
    });

    colorButton.on("click", function () {
        $(".colors > li").removeClass("active-color");
        $(this).addClass("active-color");

        const newColor = $(this).attr("data-color");
        const rgbaColorBorder = rgbToRgba(newColor, '25%');
        const rgbaColorPayItem = rgbToRgba(newColor, '5%');
        const groupStyle ={
            "background-color" : rgbaColorPayItem,
            'border-color': rgbaColorBorder
        };

        $("#buymecoffee_preview_top .buymecoffee_profile_hr").css("background-color", rgbaColorBorder);
        $("#buymecoffee_preview_top .buymecoffee_payment_item .buymecoffee_payment_input_content").css(groupStyle);
        $("#buymecoffee_preview_top button#wpm_submit_button")
            .attr('style', $(this).attr('style') + 'background-color:' + newColor + '!important;');
    });



    function rgbToRgba(rgb, alpha) {
        var values = rgb.substr(4, rgb.length - 5).split(", ");
        return "rgba(" + values[0] + ", " + values[1] + ", " + values[2] + ", " + alpha + ")";
    }
});