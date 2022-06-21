<?php

namespace buyMeCoffee\Classes;

use buyMeCoffee\Classes\View;
use \buyMeCoffee\Models\Buttons;

class DemoPage
{
    public function handleExteriorPages()
    {
        if (defined('CT_VERSION')) {
            // oxygen page compatibility
            remove_action('wp_head', 'oxy_print_cached_css', 999999);
        }
        if (isset($_GET['appreciate-your-support-bmc']) && $_GET['appreciate-your-support-bmc'] === '1') {
            wp_enqueue_style('dashicons');
            $this->loadDefaultPageTemplate();
            $this->renderPreview();
        }
    }

    public function renderPreview()
    {
        $btnController = new Buttons();
        $template = $btnController->getButton();
        echo View::make('user.payment', [
            'type' => 'button',
            'template' => $template,
        ]);
        exit();
    }

    public function loadDefaultPageTemplate()
    {
        add_filter('template_include', function ($original) {
            return locate_template(array('page.php', 'single.php', 'index.php'));
        }, 999);
    }

    /**
     * Set the posts to one
     *
     * @param  WP_Query $query
     *
     * @return void
     */
    public function pre_get_posts($query)
    {
        if ($query->is_main_query()) {
            $query->set('posts_per_page', 1);
            $query->set('ignore_sticky_posts', true);
        }
    }

}