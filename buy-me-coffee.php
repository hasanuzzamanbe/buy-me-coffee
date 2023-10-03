<?php

/*
Plugin Name: Buy me coffee widgets - fundraise into own account
Plugin URI: http://www.wpminers.com/
Description: Collect "buy me a coffee" amount directly your own Stripe and Paypal
Version: 1.0.3
Author: wpminers
Author URI: http://www.wpminers.com
License: GPLv2 or later
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
if (!defined('WPM_BMC_VERSION')) {
    define('WPM_BMC_VERSION_LITE', true);
    define('WPM_BMC_VERSION', '1.0.3');
    define('WPM_BMC_MAIN_FILE', __FILE__);
    define('WPM_BMC_URL', plugin_dir_url(__FILE__));
    define('WPM_BMC_DIR', plugin_dir_path(__FILE__));
    define('WPM_BMC_UPLOAD_DIR', '/buy-me-coffee');
    define('WPM_BMC_DEVELOPMENT', 'yes');

    class BuyMeCoffee
    {
        public function boot()
        {
            if (is_admin()) {
                $this->adminHooks();
            }
            $this->commonActions();
            $this->textDomain();
            $this->LoadEditorBlocks();
            $this->loadFiles();
            $this->registerShortcode();
            $this->registerIpnHooks();
        }

        public function loadFiles()
        {
            require WPM_BMC_DIR . 'includes/autoload.php';
            require WPM_BMC_DIR . 'includes/Models/Buttons.php';
            require WPM_BMC_DIR . 'includes/Helpers/ArrayHelper.php';
        }

        public function adminHooks()
        {
            require WPM_BMC_DIR . 'includes/autoload.php';

            //Register Admin menu
            $menu = new \BuyMeCoffee\Classes\Menu();
            $menu->register();

            // Top Level Ajax Handlers
            $ajaxHandler = new \BuyMeCoffee\Classes\AdminAjaxHandler();
            $ajaxHandler->registerEndpoints();

        }

        public function registerShortcode()
        {
            add_shortcode('buymecoffee_button', function ($args) {
                $args = shortcode_atts(array(
                    'type' => '',
                ), $args);

                $this->addAssets();

                $builder = new \BuyMeCoffee\Builder\Render();
                return $builder->render();
            });

            $demoPage = new \BuyMeCoffee\Classes\DemoPage();
            add_shortcode('buymecoffee_form', [$demoPage, 'renderFormOnly']);

            add_shortcode('buymecoffee_basic', [$demoPage, 'renderBasicTemplate']);

        }

        public function addAssets()
        {
            $vite = new \BuyMeCoffee\Classes\Vite();

            $vite::enqueueStyle('wpm_bmc_style', 'scss/public/public-style.scss', array(), WPM_BMC_VERSION);
            $vite::enqueueScript('wpm_bmc_script',  'js/BmcPublic.js', array('jquery'), WPM_BMC_VERSION, true);
            wp_localize_script('wpm_bmc_script', 'wpm_bmc', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('wpm_bmc_nonce'),
            ));
        }

        public function commonActions()
        {
            require_once WPM_BMC_DIR . 'includes/Classes/Vite.php';
            require WPM_BMC_DIR . 'includes/Controllers/SubmissionHandler.php';

            //payment methods init
            require WPM_BMC_DIR . 'includes/Builder/Methods/BaseMethods.php';
            require WPM_BMC_DIR . 'includes/Builder/Methods/Stripe/Stripe.php';
            require WPM_BMC_DIR . 'includes/Builder/Methods/PayPal/PayPal.php';
            new \BuyMeCoffee\Builder\Methods\PayPal\PayPal();
            new \BuyMeCoffee\Builder\Methods\Stripe\Stripe();

            // Submission Handler
            $submissionHandler = new \BuyMeCoffee\Controllers\SubmissionHandler();
            add_action('wp_ajax_wpm_bmc_submit', array($submissionHandler, 'handleSubmission'));
            add_action('wp_ajax_nopriv_wpm_bmc_submit', array($submissionHandler, 'handleSubmission'));

            //update payment id
            add_action('wp_ajax_wpm_bmc_update_payment', array($submissionHandler, 'updatePayment'));
            add_action('wp_ajax_nopriv_wpm_bmc_update_payment', array($submissionHandler, 'updatePayment'));
        }

        public function registerIpnHooks()
        {
            if (isset($_REQUEST['wpm_bmc_ipn_listener']) && isset($_REQUEST['method'])) {
                add_action('wp', function () {
                    $paymentMethod = sanitize_text_field($_REQUEST['method']);
                    do_action('wpm_bmc_ipn_endpoint_' . $paymentMethod);
                });
            }
        }

        public function textDomain()
        {
            load_plugin_textdomain('buy-me-coffee', false, basename(dirname(__FILE__)) . '/languages');
        }

        public function LoadEditorBlocks()
        {
            require WPM_BMC_DIR . 'includes/Builder/EditorBlocks/EditorBlocks.php';
            $pages_with_editor_button = array('post.php', 'post-new.php');
            foreach ($pages_with_editor_button as $editor_page) {
                add_action("load-{$editor_page}", array(new \BuyMeCoffee\Builder\EditorBlocks\EditorBlocks(), 'register'));
            }
        }
    }

    add_action('plugins_loaded', function () {
        (new BuyMeCoffee())->boot();
    });

    register_activation_hook(__FILE__, function ($newWorkWide) {
        require_once(WPM_BMC_DIR . 'includes/Classes/Activator.php');
        $activator = new \BuyMeCoffee\Classes\Activator();
        $activator->migrateDatabases($newWorkWide);
    });

    // disabled admin-notice on dashboard
    add_action('admin_init', function () {
        if (isset($_GET['page']) && $_GET['page'] === 'buy-me-coffee.php') {
            remove_all_actions('admin_notices');
        }
    });

    // Handle Exterior Pages
    add_action('init', function () {
        $demoPage = new \BuyMeCoffee\Classes\DemoPage();
        $demoPage->register();
    });

} else {
    add_action('admin_init', function () {
        deactivate_plugins(plugin_basename(__FILE__));
    });
}
