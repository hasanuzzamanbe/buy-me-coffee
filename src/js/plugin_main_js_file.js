import Vue from './elements';
import Router from 'vue-router';
Vue.use(Router);

import { applyFilters, addFilter, addAction, doAction } from '@wordpress/hooks';

export default class buyMeCoffee {
    constructor() {
        this.applyFilters = applyFilters;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.doAction = doAction;
        this.Vue = Vue;
        this.Router = Router;
    }

    $publicAssets(path){
        return (window.buyMeCoffeeAdmin.assets_url + path);
    }

    $get(options) {
        return window.jQuery.get(window.buyMeCoffeeAdmin.ajaxurl, options);
    }

    $adminGet(options) {
        options.action = 'buy-me-coffee_admin_ajax';
        return window.jQuery.get(window.buyMeCoffeeAdmin.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.buyMeCoffeeAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'buy-me-coffee_admin_ajax';
        return window.jQuery.post(window.buyMeCoffeeAdmin.ajaxurl, options);
    }

    $getJSON(options) {
        return window.jQuery.getJSON(window.buyMeCoffeeAdmin.ajaxurl, options);
    }
}
