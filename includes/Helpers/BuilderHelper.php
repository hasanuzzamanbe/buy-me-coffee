<?php
namespace BuyMeCoffee\Helpers;

class BuilderHelper
{
    /**
     * @var int|mixed
     */
    private static $formCount = 0;
    public static function getFormCount()
    {
        return 'bmc'. '_' . static::$formCount++;
    }

}