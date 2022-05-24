<?php
namespace buyMeCoffee\Builder\Methods;

abstract class BaseMethods
{
    public $method = '';

    public function __construct($method)
    {
        $this->method = $method;
        $this->registerHooks($method);
    }

    public function registerHooks($method)
    {
        add_action('wpm_buymecoffee_render_component_' . $method, array($this, 'render'), 10, 1);
    }

    abstract public function render($template);
}
