<?php

/*
Plugin Name: Buy me coffee - with custom payments
Plugin URI: http://www.wpminers.com/
Description: This plugin allows you to add a custom payment/donation system to your website.
Version: 1.0.0
Author: wpminers
Author URI: http://www.wpminers.com
License: A "Slug" license name e.g. GPL2
Text Domain: buy-me-coffee
*/


/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 *
 * Copyright 2019 Plugin Name LLC. All rights reserved.
 */

if (!defined('ABSPATH')) {
    exit;
}
if (!defined('BUYMECOFFEE_VERSION')) {
    define('BUYMECOFFEE_VERSION_LITE', true);
    define('BUYMECOFFEE_VERSION', '1.1.0');
    define('BUYMECOFFEE_MAIN_FILE', __FILE__);
    define('BUYMECOFFEE_URL', plugin_dir_url(__FILE__));
    define('BUYMECOFFEE_DIR', plugin_dir_path(__FILE__));
    define('BUYMECOFFEE_UPLOAD_DIR', '/buy-me-coffee');

    class buyMeCoffee
    {
        public function boot()
        {
            if (is_admin()) {
                $this->adminHooks();
            }
            $this->commonActions();
            $this->loadFiles();
            $this->registerShortcode();
        }

        public function loadFiles()
        {
            require BUYMECOFFEE_DIR . 'includes/autoload.php';
            require BUYMECOFFEE_DIR . 'includes/Models/Buttons.php';
            require BUYMECOFFEE_DIR . 'includes/Helpers/ArrayHelper.php';
        }

        public function adminHooks()
        {
            require BUYMECOFFEE_DIR . 'includes/autoload.php';

            //Register Admin menu
            $menu = new \buyMeCoffee\Classes\Menu();
            $menu->register();

            // Top Level Ajax Handlers
            $ajaxHandler = new \buyMeCoffee\Classes\AdminAjaxHandler();
            $ajaxHandler->registerEndpoints();
        }

        public function registerShortcode()
        {
            add_shortcode('buymecoffee', function ($args) {
                $args = shortcode_atts(array(
                    'type' => '',
                ), $args);

                $builder = new \buyMeCoffee\Builder\Render();
                return $builder->render();
            });
        }

        public function commonActions()
        {
            require BUYMECOFFEE_DIR . 'includes/Controllers/SubmissionHandler.php';
            require BUYMECOFFEE_DIR . 'includes/Builder/Methods/BaseMethods.php';
            require BUYMECOFFEE_DIR . 'includes/Builder/Methods/Stripe/Stripe.php';
            require BUYMECOFFEE_DIR . 'includes/Builder/Methods/PayPal/PayPal.php';

            new \buyMeCoffee\Builder\Methods\PayPal\PayPal();
            new \buyMeCoffee\Builder\Methods\Stripe\Stripe();
            // Submission Handler
            $submissionHandler = new \buyMeCoffee\Controllers\SubmissionHandler();
            add_action('wp_ajax_wpm_buymecoffee_submit', array($submissionHandler, 'handleSubmission'));
            add_action('wp_ajax_nopriv_wpm_buymecoffee_submit', array($submissionHandler, 'handleSubmission'));
        }

        public function textDomain()
        {
            load_plugin_textdomain('buy-me-coffee', false, basename(dirname(__FILE__)) . '/languages');
        }
    }

    add_action('plugins_loaded', function () {
        (new buyMeCoffee())->boot();
    });

    register_activation_hook(__FILE__, function ($newWorkWide) {
        require_once(BUYMECOFFEE_DIR . 'includes/Classes/Activator.php');
        $activator = new \buyMeCoffee\Classes\Activator();
        $activator->migrateDatabases($newWorkWide);
    });

    // disabled admin-notice on dashboard
    add_action('admin_init', function () {
        $disablePages = [
            'buy-me-coffee.php',
        ];
        if (isset($_GET['page']) && in_array($_GET['page'], $disablePages)) {
            remove_all_actions('admin_notices');
        }
    });

    // Handle Exterior Pages
    add_action('init', function () {
        $demoPage = new \buyMeCoffee\Classes\DemoPage();
        $demoPage->handleExteriorPages();
    });

} else {
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    });
}
