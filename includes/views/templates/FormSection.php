<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly   ?>

<div class="buymecoffee_form_preview_wrapper">
    <?php
    if (isset($template['formTitle']) && sanitize_text_field($template['formTitle']) === 'yes'): ?>
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php
            if (isset($_GET['for'])) {
                $template['yourName'] = sanitize_text_field($_GET['for']);
            }
            if (isset($_GET['custom_coffee'])) {
                $template['custom_coffee'] = esc_html(sanitize_text_field($_GET['custom_coffee']));
            }
            echo esc_html($template['yourName'])
            ?></span> <br/>
        a coffee
    </h3>
    <?php
    endif;
    $form = \BuyMeCoffee\Builder\Render::renderInputElements($template, $args);
    echo wp_kses($form, \BuyMeCoffee\Helpers\SanitizeHelper::allowedTags());
    ?>
</div>