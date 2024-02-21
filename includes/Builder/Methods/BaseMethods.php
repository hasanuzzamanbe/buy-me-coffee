<?php

namespace BuyMeCoffee\Builder\Methods;

use BuyMeCoffee\Helpers\BuilderHelper;
use BuyMeCoffee\Helpers\PaymentHelper;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

abstract class BaseMethods
{
    public $title = '';
    public $method = '';
    public $description = '';
    public $image = '';

    public static $methods = array();

    public function __construct($title, $method, $description, $image)
    {
        $this->title = $title;
        $this->method = $method;
        $this->description = $description;
        $this->image = $image;

        $this->registerHooks($method);

        add_action('buy-me-coffee/get_payment_settings_' . $this->method, array($this, 'getPaymentSettings'), 10, 1);

        add_filter('buymecoffee_before_save_' . $this->method, array($this, 'sanitize'), 10, 2);

        add_filter('buymecoffee_get_all_methods', array($this, 'getAllMethods'), 10, 1);

        add_action('wp_ajax_nopriv_buymecoffee_payment_confirmation_'. $this->method, array($this, 'paymentConfirmation'));
        add_action('wp_ajax_buymecoffee_payment_confirmation_' . $this->method, array($this, 'paymentConfirmation'));
    }

    public function getAllMethods()
    {
        static::$methods[$this->method] = array(
            'title' => $this->title,
            'route' => $this->method,
            'description' => $this->description,
            'image' => $this->image,
            "status" => $this->isEnabled(),
        );
        return static::$methods;
    }

    public function registerHooks($method)
    {
        add_action('buymecoffee_render_component_' . $method, array($this, 'render'), 10, 1);
    }

    abstract public function isEnabled();

    public function getMode()
    {
        $paymentSettings = $this->getSettings();
        return ($paymentSettings['payment_mode'] == 'live') ? 'live' : 'test';
    }

    public function uniqueId($id = '')
    {
        return esc_attr(BuilderHelper::getFormDynamicClass() . '_' . $id);
    }

    public function paymentConfirmation()
    {
        //return if no module extend
    }
    abstract public function render($template);

    abstract public function getPaymentSettings();

    abstract public function getSettings();

    abstract public function sanitize($settings);
}
