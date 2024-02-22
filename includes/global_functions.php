<?php

// write your global functions here
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!function_exists('buyMeCoffeeQuery')) {
    function buyMeCoffeeQuery()
    {
        if (!function_exists('buyMeCoffeeDB')) {
            include BUYMECOFFEE_DIR . 'includes/libs/wp-fluent/wp-fluent.php';
        }
        return buyMeCoffeeDB();
    }
}



