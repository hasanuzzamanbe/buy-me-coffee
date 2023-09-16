<div class="buymecoffee_form_preview_wrapper" style="<?php echo $quote ?? 'margin: 0 auto;' ?>">
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php
            if (isset($_GET['for'])) {
                $template['yourName'] = sanitize_text_field($_GET['for']);
            }
            esc_html_e($template['yourName'], 'buy-me-coffee')
            ?></span> <br/>
        a coffee
    </h3>
    <?php
    $form = \BuyMeCoffee\Builder\Render::renderInputElements($template);
    echo $form;
    ?>
</div>