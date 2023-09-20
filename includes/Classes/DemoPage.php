<?php

namespace BuyMeCoffee\Classes;

use BuyMeCoffee\Classes\View;
use BuyMeCoffee\Helpers\ArrayHelper;
use \BuyMeCoffee\Models\Buttons;
use BuyMeCoffee\Classes\Vite;

class DemoPage
{
    public function register()
    {
        $this->handleExteriorPages();
    }

    public function loadTemplateStyles()
    {
        Vite::enqueueStyle('wpm_bmc_template_style', 'scss/public/BasicTemplate.scss', array(), WPM_BMC_VERSION);
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

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if (isset($_GET['coffee-treat']) && $_GET['coffee-treat'] === '1') {
            $this->renderBasicTemplate('page');
        }
    }

    public function loadModalContent()
    {
        wp_enqueue_style('dashicons');
        $this->loadTemplateStyles();
        $btnController = new Buttons();
        $template = $btnController->getButton();

        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo View::make('templates.FormShortCode', ['template' => $template, 'type' => '',]);
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
            // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            echo View::make('templates.BasicTemplate', [ 'template' => $template,
                'type' => 'button',
                'quote' => esc_html($quote),
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