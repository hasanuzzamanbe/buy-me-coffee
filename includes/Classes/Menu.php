<?php

namespace BuyMeCoffee\Classes;

use BuyMeCoffee\Classes\View;

class Menu
{
    public function register()
    {
        add_action('admin_menu', array($this, 'addMenus'));
    }

    public function addMenus()
    {
        $menuPermission = AccessControl::hasTopLevelMenuPermission();
        if (!$menuPermission) {
            return;
        }

        $title = __('Buy Me Coffee', 'buy-me-coffee');
        global $submenu;

        $capability = 'manage_options';

        add_menu_page(
            $title,
            $title,
            $capability,
            'buy-me-coffee.php',
            [$this, 'render'],
            'dashicons-coffee',
            80
        );

        $submenu['buy-me-coffee.php']['my_profile'] = array(
            __('Dashboard', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/',
        );
        $submenu['buy-me-coffee.php']['supporters'] = array(
            __('Supporters', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/supporters',
        );
        $submenu['buy-me-coffee.php']['gateways'] = array(
            __('Gateways', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/gateway',
        );
    }

    public function render()
    {
        $this->enqueueAssets();

        ob_start();
        ?>
            <div id="buy-me-coffee_app" class="buy-me-coffee_app">
                <div class="buymecoffee_body">
                    <div class="bmc_route_wrapper">
                        <router-view></router-view>
                    </div>
                </div>
            </div>
        <?php
        echo ob_get_clean();
    }

    public function enqueueAssets()
    {
        do_action('buy-me-coffee/render_admin_app');

        wp_enqueue_script(
            'buy-me-coffee_boot',
            WPM_BMC_URL . 'assets/js/boot.js',
            array(),
            WPM_BMC_VERSION,
            true
        );

        // 3rd party developers can now add their scripts here
        do_action('buy-me-coffee/booting_admin_app');

	    wp_enqueue_script(
            'buy-me-coffee_js',
		    WPM_BMC_URL . 'assets/js/plugin-main-js-file.js',
		    array('jquery'),
		    WPM_BMC_VERSION,
		    true
	    );

        //enqueue css file
        wp_enqueue_style('buy-me-coffee_admin_css', WPM_BMC_URL . 'assets/css/element.css');

        $BuyMeCoffeeAdminVars = apply_filters('buy-me-coffee/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => WPM_BMC_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'preview_url' => site_url('?appreciate-your-support-bmc=1')
            // site_url('?buymecoffee_preview=button')
        ));


        wp_localize_script('buy-me-coffee_boot', 'BuyMeCoffeeAdmin', $BuyMeCoffeeAdminVars);
    }
}
