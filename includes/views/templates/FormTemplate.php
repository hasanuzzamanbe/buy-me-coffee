<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

    use BuyMeCoffee\Helpers\ArrayHelper as Arr;
 ?>

<div id="buymecoffee_preview_top">
    <div class="buymecoffee_profile_section">
        <style>
            <?php
                $borderStyle = Arr::get($template, 'advanced.border_style');
                $buttonStyle = Arr::get($template, 'advanced.button_style');
            ?>
            .buymecoffee_profile_hr {
                background: <?php echo esc_attr($borderStyle); ?>;
            }
            .buymecoffee_payment_input_content {
                background: <?php echo esc_attr(Arr::get($template, 'advanced.bg_style')); ?>;
                border: 1px solid <?php echo esc_attr($borderStyle); ?>;
            }
            .wpm_submit_button {
                background-color: <?php echo esc_attr($buttonStyle); ?> !important;
            }
            .wpm_submit_button:hover {
                background: <?php echo esc_attr($borderStyle); ?> !important;
            }
            .buymecoffee_currency_prefix {
                background: <?php echo esc_attr($buttonStyle); ?>;
            }

        </style>
        <div class="buymecoffee_profile_hr">
        </div>
        <div class="buymecoffee_profile_section_image">
            <img src="<?php echo esc_url($profile_image); ?>"
                 alt="Profile Image">
        </div>
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
                    <div class="buymecoffee_your_content_title buymecoffee_quote_section">
                        <div style="margin:23px;">
                            <?php if (current_user_can('manage_options')): ?>
                                <div class="buymecoffee_edit_action_wrapper" style="display: none;">
                                    <div class="buymecoffee_edit_action">✏️ Edit quotes</div>
                                    <blockquote style="display: none;">
                                        <input type="textarea" value="<?php echo esc_html($quote); ?>">
                                    </blockquote>
                                </div>
                            <?php endif; ?>
                            <blockquote class="buymecoffee_main_quote">
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