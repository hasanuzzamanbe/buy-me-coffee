<?php

namespace buyMeCoffee\Classes;

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

        $title = __('Buy Me Coffee', 'textdomain');
        global $submenu;
        add_menu_page(
            $title,
            $title,
            $menuPermission,
            'buy-me-coffee.php',
            array($this, 'enqueueAssets'),
            'dashicons-coffee',
            25
        );

        $submenu['buy-me-coffee.php']['my_profile'] = array(
            __('Plugin Dashboard', 'textdomain'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/',
        );
        $submenu['buy-me-coffee.php']['supporters'] = array(
            __('Supporters', 'textdomain'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/supporters',
        );
        $submenu['buy-me-coffee.php']['settings'] = array(
            __('Settings', 'textdomain'),
            $menuPermission,
            'admin.php?page=buy-me-coffee.php#/settings',
        );
    }

    public function enqueueAssets()
    {
        do_action('buy-me-coffee/render_admin_app');
        wp_enqueue_script(
            'buy-me-coffee_boot',
            BUYMECOFFEE_URL . 'assets/js/boot.js',
            array('jquery'),
            BUYMECOFFEE_VERSION,
            true
        );

        // 3rd party developers can now add their scripts here
        do_action('buy-me-coffee/booting_admin_app');
        wp_enqueue_script(
            'buy-me-coffee_js',
            BUYMECOFFEE_URL . 'assets/js/plugin-main-js-file.js',
            array('buy-me-coffee_boot'),
            BUYMECOFFEE_VERSION,
            true
        );

        //enque css file
        wp_enqueue_style('buy-me-coffee_admin_css', BUYMECOFFEE_URL . 'assets/css/element.css');

        $buyMeCoffeeAdminVars = apply_filters('buy-me-coffee/admin_app_vars', array(
            //'image_upload_url' => admin_url('admin-ajax.php?action=wpf_global_settings_handler&route=wpf_upload_image'),
            'assets_url' => BUYMECOFFEE_URL . 'assets/',
            'ajaxurl' => admin_url('admin-ajax.php'),
            'preview_url' => site_url('?appreciate-your-support-bmc=1')
            // site_url('?buymecoffee_preview=button')
        ));

        wp_localize_script('buy-me-coffee_boot', 'buyMeCoffeeAdmin', $buyMeCoffeeAdminVars);
    }
}
