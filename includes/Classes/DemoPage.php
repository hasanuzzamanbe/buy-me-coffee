<?php

namespace BuyMeCoffee\Classes;

use BuyMeCoffee\Classes\View;
use BuyMeCoffee\Helpers\ArrayHelper;
use \BuyMeCoffee\Models\Buttons;

class DemoPage
{
    public function register()
    {
       $this->handleExteriorPages();
    }

    public function loadTemplateStyles()
    {
        wp_enqueue_style('wpm_bmc_template_style', WPM_BMC_URL . 'assets/css/BasicTemplate.css', array(), WPM_BMC_VERSION);
    }

    public function renderFormOnly()
    {
        $this->loadTemplateStyles();
        $btnController = new Buttons();
        $template = $btnController->getButton();
        ob_start();
        include WPM_BMC_DIR . 'includes/views/templates/FormShortCode.php';
        return ob_get_clean();
    }

    public function handleExteriorPages()
    {
        if (defined('CT_VERSION')) {
            // oxygen page compatibility
            remove_action('wp_head', 'oxy_print_cached_css', 999999);
        }
        if (isset($_GET['coffee-treet']) && $_GET['coffee-treet'] === '1') {
            $this->renderBasicTemplate('page');
        }
    }

    public function loadModalContent()
    {
        wp_enqueue_style('dashicons');
        $this->loadTemplateStyles();
        $btnController = new Buttons();
        $template = $btnController->getButton();

        echo View::make('templates.FormShortCode', [
            'type' => '',
            'template' => $template,
        ]);
    }

    public function renderBasicTemplate($type)
    {
        if (is_page() || $type === 'page') {
            wp_enqueue_style('dashicons');
            $this->loadDefaultPageTemplate();
            $this->loadTemplateStyles();

            $btnController = new Buttons();
            $template = $btnController->getButton();

            $quote = ArrayHelper::get($template, 'advanced.quote', false);
            echo View::make('templates.BasicTemplate', [
                'type' => 'button',
                'template' => $template,
                'quote' => $quote,
            ]);
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