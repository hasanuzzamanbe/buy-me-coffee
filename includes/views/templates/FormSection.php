<div class="buymecoffee_form_preview_wrapper">
    <h3 class="buymecoffee_form_to">
        Buy
        <span class="buymecoffee_form_to"><?php esc_html_e($template['yourName'], 'buymecoffee') ?></span> <br/>
        a coffee
    </h3>
    <?php
    $form =  \BuyMeCoffee\Builder\Render::renderInputElements();
    echo $form;
    ?>
</div>