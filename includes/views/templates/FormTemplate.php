<div id="buymecoffee_preview_top">
    <div class="buymecoffee_profile_section">
        <div class="buymecoffee_profile_hr">
        </div>
        <div class="buymecoffee_profile_section_image">
            <img src="<?php echo esc_url($profile_image); ?>"
                 alt="Profile Image">
        </div>
<!--        <h1 style="font-size:23px;text-align:center;color: #6b6b6b;" class="buymecoffee_profile_section_name">--><?php //echo esc_html($name); ?><!--</h1>-->

    </div>
    <div class="buymecoffee_preview_header">
    </div>
    <div class="buymecoffee_preview_body">
        <?php

        // check if receipt page
        if (isset($_REQUEST['buymecoffee_success']) && isset($_REQUEST['hash'])) {
            $hash = sanitize_text_field($_REQUEST['hash']);
            $paymentData = \BuyMeCoffee\Models\Supporters::getByHash($hash);
            include BUYMECOFFEE_DIR . 'includes/views/templates/Confirmation.php';
        } else {
            include BUYMECOFFEE_DIR . 'includes/views/templates/FormSection.php';
        }

        if ($quote && !isset($_REQUEST['buymecoffee_success'])):
            ?>
            <div class="buymecoffee_your_content_wrapper">
                <div class="buymecoffee_your_content">
                    <div class="buymecoffee_your_content_title">
                        <div style="margin:23px;">
                            <blockquote>
                                <p class="bmc_appreciation_title"><?php echo esc_html($quote); ?></p>
                            </blockquote>
                        </div>
                    </div>
                    <div class="buymecoffee_your_content_body">
                        <p></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>