<?php

namespace BuyMeCoffee\Helpers;


if (!defined('ABSPATH')) exit; // Exit if accessed directly


class SanitizeHelper
{
    public static function sanitizeText($dataArray) :array
    {
        $sanitizedData = [];
        foreach ($dataArray as $key => $value) {
            $sanitizedData[$key] = sanitize_text_field($value);
        }
        return $sanitizedData;
    }

    public static function allowedTags() : array
    {
        $allowedTags = [];
        foreach (['input','label','div','span','p','select','option','textarea'] as $tag){
            $allowedTags[$tag] = [
                'type' => [],
                'name' => [],
                'value' => [],
                'autocomplete' => [],
                'placeholder' => [],
                'data-required' => [],
                'data-type' => [],
                'data-payment_selected' => [],
                'id' => [],
                'class' => [],
                'required' => [],
                'disabled' => [],
                'checked' => [],
                'selected' => [],
                'input' => [],
                'image' => [],
                'svg' => [],
                'img' => array(
                    'title' => array(),
                    'src'	=> array(),
                    'alt'	=> array(),
                ),
                'for' => [],
                'style' => [],
            ];
        }

        return $allowedTags;
    }
}