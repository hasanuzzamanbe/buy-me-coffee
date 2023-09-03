<?php

namespace buyMeCoffee\Classes;

use buyMeCoffee\Classes\View;
use \buyMeCoffee\Models\Buttons;

class DemoPage
{
    public function register()
    {
       $this->handleExteriorPages();
    }

    public function loadTemplateStyles()
    {
        wp_enqueue_style('wpm_buymecoffee_template_style', BUYMECOFFEE_URL . 'assets/css/BasicTemplate.css', array(), BUYMECOFFEE_VERSION);
    }

    public function renderFormOnly()
    {
        $this->loadTemplateStyles();
        $btnController = new Buttons();
        $template = $btnController->getButton();
        ob_start();
        include BUYMECOFFEE_DIR . 'includes/views/templates/FormShortCode.php';
        return ob_get_clean();
    }

    public function handleExteriorPages()
    {
        if (defined('CT_VERSION')) {
            // oxygen page compatibility
            remove_action('wp_head', 'oxy_print_cached_css', 999999);
        }
        if (isset($_GET['appreciate-your-support-bmc']) && $_GET['appreciate-your-support-bmc'] === '1') {
            $this->renderBasicTemplate($type = 'page');
        }
    }

    public function loadModalContent()
    {
        wp_enqueue_style('dashicons');
        $this->loadTemplateStyles();
        $btnController = new Buttons();
        $template = $btnController->getButton();

        echo View::make('templates.FormTemplate', [
            'type' => '',
            'template' => $template,
        ]);
    }
    public function renderBasicTemplate($type = '')
    {
        wp_enqueue_style('dashicons');
        $this->loadDefaultPageTemplate();
        $this->loadTemplateStyles();

        $btnController = new Buttons();
        $template = $btnController->getButton();

        echo View::make('templates.BasicTemplate', [
            'type' => 'button',
            'template' => $template,
        ]);

        if ($type === 'page') {
            exit();
        }
    }

    public function loadDefaultPageTemplate()
    {
        add_filter('template_include', function ($original) {
            return locate_template(array('page.php', 'single.php', 'index.php'));
        }, 999);
    }
}