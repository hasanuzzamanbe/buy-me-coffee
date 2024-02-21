<div class="buymecoffee_form_preview_wrapper">
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php
            if (isset($_GET['for'])) {
                $template['yourName'] = esc_html($_GET['for']);
            }
            if (isset($_GET['custom_coffee'])) {
                $template['custom_coffee'] = esc_html($_GET['custom_coffee']);
            }
            echo esc_html($template['yourName'])
            ?></span> <br/>
        a coffee
    </h3>
    <?php
    ob_start();
    $form = \BuyMeCoffee\Builder\Render::renderInputElements($template, $args);

    echo wp_kses($form, \BuyMeCoffee\Helpers\SanitizeHelper::allowedTags());
    ?>
</div>