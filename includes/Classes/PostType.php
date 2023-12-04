<?php

namespace BuyMeCoffee\Classes;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Register and initialize custom post type for buy-me-coffee
 * @since 1.0.0
 */
class PostType
{
    public function __construct()
    {
        add_action('init', array($this, 'register'));
    }

    public function register()
    {
        $args = array(
            'capability_type' => 'post',
            'public' => false,
            'show_ui' => false,
        );
        register_post_type('buy-me-coffee', $args);
    }
}
