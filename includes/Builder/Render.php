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

    public function renderInputElements()
    {
        $elementName = 'textarea';
        $attributes = array(
            'data-required' => 'no',
            'data-type' => 'textarea',
            'name' => 'wpm-message',
            'placeholder' => 'Write your message here',
            'class' => 'wpm-message',
            'id' => 'wpm-message',
        );
        ob_start();
        ?>
        <div data-element_type="<?php echo $elementName; ?>" ">
                    <label for="wpm-message">Message</label>
                    <div class="wpf_input_content">
                <textarea <?php echo $this->builtAttributes($attributes); ?>></textarea>
            </div>
        </div>
        <?php
        $content = ob_get_clean();
        return $content;
    }
    public function builtAttributes($attributes)
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
