<?php

namespace BuyMeCoffee\Builder;

use BuyMeCoffee\Controllers\PaymentHandler;
use BuyMeCoffee\Models\Buttons;
use BuyMeCoffee\Helpers\ArrayHelper as Arr;
use BuyMeCoffee\Classes\DemoPage;
use BuyMeCoffee\Helpers\PaymentHelper;


class Render
{

    public function render()
    {
        $template = (new Buttons())->getButton();

        $buttonText = Arr::get($template, 'buttonText');
        $bgColor = Arr::get($template, 'advanced.bgColor');
        $color = Arr::get($template, 'advanced.color');
        $minWidth = Arr::get($template, 'advanced.minWidth');
        $fontSize = Arr::get($template, 'advanced.fontSize');
        $radius = Arr::get($template, 'advanced.radius');

        $paymentPageUrl = site_url('?coffee-treet=1');
        $openMode = Arr::get($template, 'openMode');

        $styling = "<style>.wpm-buymecoffee-container .wpm-buymecoffee-button{
            outline: none;
            box-shadow: none;
            padding: 10px 23px;
            line-height: 0px;
            background-color: $bgColor;
            color: $color;
            min-width: {$minWidth}px;
            font-size: {$fontSize}px;
            border-radius: {$radius}px;
            cursor: pointer;
        }
        button.wpm-buymecoffee-button {
            height: 50px;
        }
        .wpm-buymecoffee-button:hover {
            box-shadow: 4px 3px 6px 2px #ccc;
        }
        </style>";

        ob_start();
        echo $styling;
        ?>
        <div class="wpm-buymecoffee-container">
            <!--  The Modal button -->
            <?php
            if ($openMode === 'modal') {
                ?>
                    <button class="wpm-buymecoffee-button" id="bmc_open_modal_btn"  ><?php echo $buttonText; ?></button>
                <?php
            } else {
                ?>
                    <a class="wpm-buymecoffee-button" target="_blank" href="<?php echo esc_url($paymentPageUrl);?>"><?php echo $buttonText; ?></a>
                <?php
            } ?>
            <div id="bmc_modal_wrapper" class="modal">
                <div class="bmc_modal_content">
                    <span class="close">&times;</span>
                    <?php (new DemoPage())->loadModalContent(); ?>
                </div>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function renderInputElements()
    {
        static::addAssets();

        $nameAttributes = array(
            'data-required' => 'no',
            'data-type' => 'text',
            'name' => 'wpm-supporter-name',
            'placeholder' => 'John Doe',
            'class' => 'wpm-supporter-name',
            'id' => 'wpm-supporter-name',
        );

        $emailAttributes = array(
            'data-required' => 'no',
            'data-type' => 'email',
            'name' => 'wpm-supporter-email',
            'placeholder' => 'example@domain.com',
            'class' => 'wpm-supporter-email',
            'id' => 'wpm-supporter-email',
        );

        $msgAttributes = array(
            'data-required' => 'yes',
            'data-type' => 'textarea',
            'name' => 'wpm-supporter-message',
            'placeholder' => 'Love to hear from you!',
            'class' => 'wpm-supporter-message',
            'id' => 'wpm-supporter-message',
        );

        $btnAttributes = array(
            'data-required' => 'no',
            'data-type' => 'submit',
            'name' => 'wpm_submit_button',
            'placeholder' => 'Submit',
            'class' => 'wpm_submit_button',
            'id' => 'wpm_submit_button',
        );
        $template = (new Buttons())->getButton();
        $enableName= Arr::get($template, 'enableName') == 'yes' ? true : false;
        $enableEmail = Arr::get($template, 'enableEmail') == 'yes' ? true : false;
        $enableMsg = Arr::get($template, 'enableMessage') == 'yes' ? true : false;
        $defaultAmount = intval(Arr::get($template, 'defaultAmount', '5'));

        $currency = Arr::get($template, 'currency', 'USD');
        $symbool = PaymentHelper::currencySymbol();

        ob_start();
        ?>
        <form class="wpm_bmc_form" data-wpm_currency="<?php echo esc_html_e($currency) ;?>">
            <div class="wpm_bmc_payment_item">
                <div class="wpm_bmc_input_content">
                    <div class="wpm_bmc_coffee_selector">
                        <img width="50" src="<?php echo WPM_BMC_URL . 'assets/images/coffee.png'; ?>" >
                        <span>x</span>

                        <div class="bmc_coffee">
                            <input id="radio_1" value="1" class="coffee-select" name="radio-group" type="radio" checked>
                            <label for="radio_1">1</label>

                            <input id="radio_2" value="2" class="coffee-select" name="radio-group" type="radio">
                            <label for="radio_2">2</label>

                            <input id="radio_3" value="3" class="coffee-select" name="radio-group" type="radio">
                            <label for="radio_3">3</label>
                        </div>
                        <input class="wpm_bmc_custom_quantity" type="number" value="5" data-quantity="5">

                    </div>
                </div>
            </div>
<!--   This custom quantity will update in any future feature       /-->
            <div class="wpm_bmc_payment_item" style="display: none !important; align-items: center;">
                <div class="wpm_bmc_input_content">
                    <div style="display: flex;">
                        <span class="wpm_bmc_currency_prefix"><?php echo esc_html($symbool);?></span>
                        <input type="number" class="wpm_bmc_payment"
                            style="
                                border-top-left-radius: 0px;
                                border-bottom-left-radius: 0px;
                                border: 1px solid #ffe3b9;
                                height: 60px;
                                font-size:33px;
                                padding: 0px 20px; margin-bottom: 14px;"
                            value="<?php echo $defaultAmount; ?>"
                            data-price="<?php echo $defaultAmount * 100; ?>"
                            type="text">
                    </div>
                </div>
            </div>
            <?php if ($enableName): ?>
                <div data-element_type="input" class="wpm_bmc_form_item">
<!--                        <label for="wpm-supporter-name">Name</label>-->
                        <div class="wpm_bmc_input_content">
                            <input <?php echo static::builtAttributes($nameAttributes); ?>></input>
                        </div>
                </div>
            <?php endif; ?>

            <?php if ($enableEmail): ?>
                <div data-element_type="email" class="wpm_bmc_form_item">
<!--                        <label for="wpm-message">Email</label>-->
                        <div class="wpm_bmc_input_content">
                            <input <?php echo static::builtAttributes($emailAttributes); ?>></input>
                        </div>
                </div>
            <?php endif; ?>

            <?php if ($enableMsg): ?>
                <div data-element_type="textarea" class="wpm_bmc_form_item">
<!--                    <label for="wpm-message">Message</label>-->
                    <div class="wpm_bmc_input_content">
                        <textarea rows="6" <?php echo static::builtAttributes($msgAttributes); ?>></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <div class="wpm_bmc_form_item wpm_bmc_pay_methods">
                <div class="wpm_bmc_pay_method" data-payment_selected="none">
                    <?php echo static::payMethod($template); ?>
                </div>
            </div>

            <div data-element_type="submit" class="wpm_bmc_form_item">
                <div class="wpm_bmc_input_content">
                    <button <?php echo static::builtAttributes($btnAttributes); ?>>Support
                        <div class="wpm_loading_svg">
                            <svg version="1.1" id="loader-1" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px"
                                viewBox="0 0 40 40" enable-background="new 0 0 40 40" xml:space="preserve">
                                <path opacity="0.2" fill="#000"
                                    d="M20.201,5.169c-8.254,0-14.946,6.692-14.946,14.946c0,8.255,6.692,14.946,14.946,14.946 s14.946-6.691,14.946-14.946C35.146,11.861,28.455,5.169,20.201,5.169z M20.201,31.749c-6.425,0-11.634-5.208-11.634-11.634 c0-6.425,5.209-11.634,11.634-11.634c6.425,0,11.633,5.209,11.633,11.634C31.834,26.541,26.626,31.749,20.201,31.749z"/>
                                <path fill="#000"
                                    d="M26.013,10.047l1.654-2.866c-2.198-1.272-4.743-2.012-7.466-2.012h0v3.312h0 C22.32,8.481,24.301,9.057,26.013,10.047z">
                                    <animateTransform attributeType="xml" attributeName="transform" type="rotate" from="0 20 20"
                                                    to="360 20 20" dur="0.5s" repeatCount="indefinite"/>
                                </path></svg>
                        </div>
                        <?php echo $symbool; ?></php> <span class="wpm_payment_total_amount"><?php echo $defaultAmount;?></span>
                    </button>
                </div>
            </div>
            <p class="wpm_bmc_no_signup">No signup required</p>
        </form>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function payMethod($template)
    {
        $methods = PaymentHandler::getAllMethods();
        $hasActiveMethod = false;

        foreach ($methods as $method) {
            if (isset($method['status']) && $method['status'] == 'yes'){
                $hasActiveMethod = true;
                do_action( 'wpm_bmc_render_component_' . $method['route'], $template );
            }
        }
        if (!$hasActiveMethod) {
            return '<p style="color:#fb7373; font-size:16px; margin: 0 auto;">Please active at least one payment method!</p>';
        }
    }

    public static function addAssets()
    {
        wp_enqueue_style('wpm_bmc_css', WPM_BMC_URL . 'assets/css/public-style.css', array(), WPM_BMC_VERSION);
        wp_enqueue_script('wpm_bmc', WPM_BMC_URL . 'assets/js/BmcPublic.js', array('jquery'), WPM_BMC_VERSION, true);
        wp_localize_script('wpm_bmc', 'wpm_bmc_general', array(
            'ajax_url'  => admin_url('admin-ajax.php')
        ));
    }

    public static function builtAttributes($attributes)
    {
        $atts = ' ';
        foreach ($attributes as $attributeKey => $attribute) {
            if (is_array($attribute)) {
                $attribute = json_encode($attribute);
            }
            $atts .= $attributeKey . "='" . htmlspecialchars($attribute, ENT_QUOTES) . "' ";
        }
        return $atts;
    }
}
