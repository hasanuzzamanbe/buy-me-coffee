<?php

// write your global functions here
if (!defined('ABSPATH')) exit; // Exit if accessed directly

if (!function_exists('wpFluent')) {
    include WPM_BMC_DIR . 'includes/libs/wp-fluent/wp-fluent.php';
}

if (!function_exists('wpmBmcDB')) {
    function wpmBmcDB()
    {
        if (!function_exists('wpFluent')) {
            include WPM_BMC_DIR . 'includes/libs/wp-fluent/wp-fluent.php';
        }
        return wpFluent();
    }
}



