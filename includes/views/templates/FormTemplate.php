<div id="buymecoffee_preview_top">
    <div class="buymecoffee_profile_section">
        <div class="buymecoffee_profile_hr">
        </div>
        <div class="buymecoffee_profile_section_image">
            <img src="<?php echo BUYMECOFFEE_URL . 'assets/images/coffee.png';?>"
                 alt="Profile Image">
        </div>

    </div>
    <div class="buymecoffee_preview_header">
        <h3><?php esc_html_e('', 'buymecoffee') ?></h3>
    </div>
    <div class="buymecoffee_preview_body">
        <div class="buymecoffee_form_preview_wrapper">
            <h3 class="buymecoffee_form_to">
                Buy
                <span class="buymecoffee_form_to"><?php esc_html_e($template['yourName'], 'buymecoffee') ?></span> <br/>
                a coffee
            </h3>
            <?php
            $form =  \buyMeCoffee\Builder\Render::renderInputElements();
            echo $form;
            ?>
        </div>
        <div class="buymecoffee_your_content_wrapper">
            <div class="buymecoffee_your_content">
                <div class="buymecoffee_your_content_title">
                    <div style="margin-bottom:23px;">
                        <h4 class="bmc_appreciation_title">Thanks for your appreciation.</h4>
                    </div>
                </div>
                <div class="buymecoffee_your_content_body">
                    <p></p>
                </div>
            </div>
        </div>
    </div>
</div>