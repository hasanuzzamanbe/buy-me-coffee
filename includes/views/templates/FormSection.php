<div class="buymecoffee_form_preview_wrapper">
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php
            //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $getData = $_GET;
            if (isset($getData['for'])) {
                $template['yourName'] = sanitize_text_field($getData['for']);
            }
            if (isset($getData['custom_coffee'])) {
                $template['custom_coffee'] = sanitize_text_field($getData['custom_coffee']);
            }
            echo esc_html($template['yourName'])
            ?></span> <br/>
        a coffee
    </h3>
    <?php
    $form = \BuyMeCoffee\Builder\Render::renderInputElements($template, $args);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $form;
    ?>
</div>