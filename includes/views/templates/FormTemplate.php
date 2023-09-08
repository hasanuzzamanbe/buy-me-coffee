<div id="buymecoffee_preview_top">
    <div class="buymecoffee_profile_section">
        <div class="buymecoffee_profile_hr">
        </div>
        <div class="buymecoffee_profile_section_image">
            <img src="<?php echo WPM_BMC_URL . 'assets/images/coffee.png';?>"
                 alt="Profile Image">
        </div>

    </div>
    <div class="buymecoffee_preview_header">
        <h3><?php esc_html_e('', 'buymecoffee') ?></h3>
    </div>
    <div class="buymecoffee_preview_body">
        <?php
            include WPM_BMC_DIR . 'includes/views/templates/FormSection.php';

        if ($quote):
        ?>
        <div class="buymecoffee_your_content_wrapper">
            <div class="buymecoffee_your_content">
                <div class="buymecoffee_your_content_title">
                    <div style="margin-bottom:23px;">
                        <blockquote>
                        <h4 class="bmc_appreciation_title"><?php echo esc_html($quote);?></h4>
                        </blockquote>
                    </div>
                </div>
                <div class="buymecoffee_your_content_body">
                    <p></p>
                </div>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>