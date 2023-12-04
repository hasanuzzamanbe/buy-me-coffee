<?php

namespace BuyMeCoffee\Helpers;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class BuilderHelper
{
    /**
     * @var int|mixed
     */
    private static $formCount = 0;

    public static function getFormDynamicClass()
    {
        return 'bmc' . '_form_' . static::$formCount++;
    }

}