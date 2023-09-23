<?php

namespace BuyMeCoffee\Classes;

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
            68
        );

        $submenu['buy-me-coffee.php']['my_profile'] = array(
            __('Dashboard', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/',
        );
        $submenu['buy-me-coffee.php']['settings'] = array(
            __('Settings', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/settings',
        );
        $submenu['buy-me-coffee.php']['gateways'] = array(
            __('Gateways', 'buy-me-coffee'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/gateway',
        );
    }

    public function render()
    {
        wp_enqueue_media();
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
        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        echo ob_get_clean();
    }

    public function enqueueAssets()
    {
        do_action('buy-me-coffee/render_admin_app');

        Vite::enqueueScript(
            'buy-me-coffee_boot',
            'js/boot.js',
            array(),
            WPM_BMC_VERSION,
            true
        );

        // 3rd party developers can now add their scripts here
        do_action('buy-me-coffee/booting_admin_app');

        Vite::enqueueScript(
            'buy-me-coffee_js',
            'js/main.js',
            array('jquery'),
            WPM_BMC_VERSION,
            true
        );

        //enqueue css file using wp_enqueue (already compiled with vite)
        //Vite::enqueueStyle('buy-me-coffee_admin_css', 'scss/admin/app.scss');

        $BuyMeCoffeeAdminVars = apply_filters('buy-me-coffee/admin_app_vars', array(
            'assets_url' => Vite::staticPath(),
            'ajaxurl' => admin_url('admin-ajax.php'),
            'preview_url' => site_url('?send_coffee=1'),
            'nonce' => wp_create_nonce('wpm_bmc_nonce'),
        ));


        wp_localize_script('buy-me-coffee_boot', 'BuyMeCoffeeAdmin', $BuyMeCoffeeAdminVars);
    }
}
