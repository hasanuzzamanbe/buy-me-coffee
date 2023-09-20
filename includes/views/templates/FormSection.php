<div class="buymecoffee_form_preview_wrapper" style="<?php echo esc_attr($quote) ?? 'margin: 0 auto;' ?>">
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php
            //phpcs:ignore WordPress.Security.NonceVerification.Recommended
            $getData = $_GET;
            if (isset($getData['for'])) {
                $template['yourName'] = sanitize_text_field($getData['for']);
            }
            esc_html($template['yourName'])
            ?></span> <br/>
        a coffee
    </h3>
    <?php
    $form = \BuyMeCoffee\Builder\Render::renderInputElements($template);
    // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    echo $form;
    ?>
</div>