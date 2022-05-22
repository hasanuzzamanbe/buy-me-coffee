<?php

namespace buyMeCoffee\Builder;

use buyMeCoffee\Controllers\ButtonControllers;
use buyMeCoffee\Helpers\ArrayHelper;


class Render
{

    public function render()
    {
        $template = (new ButtonControllers())->getButton();

        $buttonText = ArrayHelper::get($template, 'buttonText');
        $bgColor = ArrayHelper::get($template, 'advanced.bgColor');
        $color = ArrayHelper::get($template, 'advanced.color');
        $minWidth = ArrayHelper::get($template, 'advanced.minWidth');
        $fontSize = ArrayHelper::get($template, 'advanced.fontSize');
        $radius = ArrayHelper::get($template, 'advanced.radius');

        $paymentPageUrl = site_url('?buymecoffee_payment_page=1');

        $styling = "<style>.wpm-buymecoffee-container .wpm-buymecoffee-button{
            outline: none;
            box-shadow: none;
            font-family: cursive;
            background-color: $bgColor;
            color: $color;
            min-width: {$minWidth}px;
            font-size: {$fontSize}px;
            border-radius: {$radius}px;
        }</style>";

        ob_start();
        echo $styling;
        ?>
        <div class="wpm-buymecoffee-container">
            <a target="_blank" href="<?php echo $paymentPageUrl; ?>">
                <button class="wpm-buymecoffee-button">
                    <?php echo  $buttonText; ?>
                </button>
            </a>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }

    public static function renderInputElements()
    {
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
            'data-required' => 'no',
            'data-type' => 'textarea',
            'name' => 'wpm-message',
            'placeholder' => 'Write your message here',
            'class' => 'wpm-message',
            'id' => 'wpm-message',
        );

        $btnAttributes = array(
            'data-required' => 'no',
            'data-type' => 'submit',
            'name' => 'wpm-submit',
            'placeholder' => 'Submit',
            'class' => 'wpm-bmc-submit',
            'id' => 'wpm-submit',
        );
        $template = (new ButtonControllers())->getButton();
        $enableName= ArrayHelper::get($template, 'enableName') == 'yes' ? true : false;
        $enableEmail = ArrayHelper::get($template, 'enableEmail') == 'yes' ? true : false;
        $enableMsg = ArrayHelper::get($template, 'enableMessage') == 'yes' ? true : false;

        ob_start();
        ?>
        <form>
            <div class="wpm_bmc_payment_item" style="display: flex;align-items: center;">
                <img width="60px;" src="<?php echo BUYMECOFFEE_URL . 'assets/images/coffee.png'; ?>" alt="Paypal">
                <div class="wpm_bmc_input_content">
                    <div style="display: flex;">
                        <span class="wpm_bmc_currency_prefix">$</span>
                        <input type="number"
                            style="
                                border-top-left-radius: 0px;
                                border-bottom-left-radius: 0px;
                                border: 1px solid #ffe3b9;
                                height: 60px;
                                font-size:33px;
                                padding: 0px 20px;"
                            value="5"
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

            <div data-element_type="textarea" class="wpm_bmc_form_item">
                <div class="wpm_bmc_input_content">
                    <button <?php echo self::builtAttributes($btnAttributes); ?>>Support</button>
                </div>
            </div>
        </form>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
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
