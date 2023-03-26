<?php

namespace buyMeCoffee\Builder;

use buyMeCoffee\Models\Buttons;
use buyMeCoffee\Helpers\ArrayHelper;


class Render
{

    public function render()
    {
        $template = (new Buttons())->getButton();

        $buttonText = ArrayHelper::get($template, 'buttonText');
        $bgColor = ArrayHelper::get($template, 'advanced.bgColor');
        $color = ArrayHelper::get($template, 'advanced.color');
        $minWidth = ArrayHelper::get($template, 'advanced.minWidth');
        $fontSize = ArrayHelper::get($template, 'advanced.fontSize');
        $radius = ArrayHelper::get($template, 'advanced.radius');

        $paymentPageUrl = site_url('?appreciate-your-support-bmc=1');

        $styling = "<style>.wpm-buymecoffee-container .wpm-buymecoffee-button{
            outline: none;
            box-shadow: none;
            height: 50px;
            line-height: 0px;
            background-color: $bgColor;
            color: $color;
            min-width: {$minWidth}px;
            font-size: {$fontSize}px;
            border-radius: {$radius}px;
            cursor: pointer;
        }
        .wpm-buymecoffee-button:hover {
            box-shadow: 4px 3px 6px 2px #ccc;
        }
        </style>";

        ob_start();
        echo $styling;
        ?>
        <div class="wpm-buymecoffee-container">
            <a target="_blank" href="<?php echo $paymentPageUrl; ?>">
                <button class="wpm-buymecoffee-button">
                    <?php echo $buttonText; ?>
                </button>
            </a>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function renderInputElements()
    {
        self::addAssets();

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
            'placeholder' => 'Write your message here',
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
        $enableName= ArrayHelper::get($template, 'enableName') == 'yes' ? true : false;
        $enableEmail = ArrayHelper::get($template, 'enableEmail') == 'yes' ? true : false;
        $enableMsg = ArrayHelper::get($template, 'enableMessage') == 'yes' ? true : false;

        ob_start();
        ?>
        <form class="wpm_buymecoffee_form">
            <div class="wpm_bmc_payment_item" style="display: flex;align-items: center;">
                <img width="60px;" src="<?php echo BUYMECOFFEE_URL . 'assets/images/coffee.png'; ?>" alt="Paypal">
                <div class="wpm_bmc_input_content">
                    <div style="display: flex;">
                        <span class="wpm_bmc_currency_prefix">$</span>
                        <input type="number" class="wpm_buymecoffee_payment"
                            style="
                                border-top-left-radius: 0px;
                                border-bottom-left-radius: 0px;
                                border: 1px solid #ffe3b9;
                                height: 60px;
                                font-size:33px;
                                padding: 0px 20px;"
                            value="5"
                            data-price="5"
                            type="text">
                    </div>
                </div>
            </div>
            <?php if ($enableName): ?>
                <div data-element_type="input" class="wpm_bmc_form_item">
                        <label for="wpm-supporter-name">Name</label>
                        <div class="wpm_bmc_input_content">
                            <input <?php echo self::builtAttributes($nameAttributes); ?>></input>
                        </div>
                </div>
            <?php endif; ?>

            <?php if ($enableEmail): ?>
                <div data-element_type="email" class="wpm_bmc_form_item">
                        <label for="wpm-message">Email</label>
                        <div class="wpm_bmc_input_content">
                            <input <?php echo self::builtAttributes($emailAttributes); ?>></input>
                        </div>
                </div>
            <?php endif; ?>

            <?php if ($enableMsg): ?>
                <div data-element_type="textarea" class="wpm_bmc_form_item">
                    <label for="wpm-message">Message</label>
                    <div class="wpm_bmc_input_content">
                        <textarea <?php echo self::builtAttributes($msgAttributes); ?>></textarea>
                    </div>
                </div>
            <?php endif; ?>

            <div class="wpm_bmc_form_item wpm_bmc_pay_methods">
                <div class="wpm_bmc_pay_method">
                    <?php echo self::payMethod($template); ?>
                </div>
            </div>

            <div data-element_type="submit" class="wpm_bmc_form_item">
                <div class="wpm_bmc_input_content">
                    <button <?php echo self::builtAttributes($btnAttributes); ?>>Support
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
                    </button>
                </div>
            </div>
        </form>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function payMethod($template)
    {
        $methods = ArrayHelper::get($template, 'methods');
        foreach ($methods as $method) {
            do_action( 'wpm_buymecoffee_render_component_' . $method, $template );
        }
    }

    public static function addAssets()
    {
        wp_enqueue_style('wpm_buymecoffee_css', BUYMECOFFEE_URL . 'assets/css/public-style.css', array(), BUYMECOFFEE_VERSION);
        wp_enqueue_script('wpm_buymecoffee', BUYMECOFFEE_URL . 'assets/js/BmcPublic.js', array('jquery'), BUYMECOFFEE_VERSION, true);
        wp_localize_script('wpm_buymecoffee', 'wpm_buymecoffee_general', array(
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
