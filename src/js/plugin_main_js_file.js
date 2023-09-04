import app from './elements.js';

import {
    applyFilters,
    addFilter,
    addAction,
    doAction,
    removeAllActions
} from '@wordpress/hooks';

const moment = require('moment');
require('moment/locale/en-gb');
moment.locale('en-gb');

const appStartTime = new Date();

export default class BuyMeCoffee {
    constructor() {
        this.doAction = doAction;
        this.addFilter = addFilter;
        this.addAction = addAction;
        this.applyFilters = applyFilters;
        this.removeAllActions = removeAllActions;
        this.appVars = window.BuyMeCoffeeAdmin;
        this.app = this.extendVueConstructor();
    }

    extendVueConstructor() {
        const self = this;
        app.mixin({
            methods: {
                addFilter,
                applyFilters,
                doAction,
                addAction,
                removeAllActions,
                longLocalDate: self.longLocalDate,
                longLocalDateTime: self.longLocalDateTime,
                dateTimeFormat: self.dateTimeFormat,
                localDate: self.localDate,
                ucFirst: self.ucFirst,
                ucWords: self.ucWords,
                slugify: self.slugify,
                moment: moment,
                $get: self.$get,
                $post: self.$post,
                $adminGet: self.$adminGet,
                $adminPost: self.$adminPost,
                // $del: self.$del,
                // $put: self.$put,
                // $patch: self.$patch,
                $handleSuccess: self.handleSuccess,
                $handleError: self.handleError,
                $saveData: self.saveData,
                $getData: self.getData,
                $timeDiff: self.humanDiffTime,
                $waitingTime: self.waitingTime,
                convertToText: self.convertToText,
                $setTitle(title) {
                    document.title = title;
                }
            }
        });

        return app;
    }
    registerBlock(blockLocation, blockName, block) {
        this.addFilter(blockLocation, this.appVars.slug, function (components) {
            components[blockName] = block;
            return components;
        });
    }

    registerTopMenu(title, route) {
        if (!title || !route.name || !route.path || !route.component) {
            return;
        }

        this.addFilter('buy_me_coffee_top_menus', this.appVars.slug, function (menus) {
            menus = menus.filter(m => m.route !== route.name);
            menus.push({
                route: route.name,
                title: title
            });
            return menus;
        });

        this.addFilter('buy_me_coffee_global_routes', this.appVars.slug, function (routes) {
            routes = routes.filter(r => r.name !== route.name);
            routes.push(route);
            return routes;
        });
    }

    $publicAssets(path){
        return (window.BuyMeCoffeeAdmin.assets_url + path);
    }

    $get(options) {
        return window.jQuery.get(window.BuyMeCoffeeAdmin.ajaxurl, options);
    }

    $adminGet(options) {
        options.action = 'buy-me-coffee_admin_ajax';
        return window.jQuery.get(window.BuyMeCoffeeAdmin.ajaxurl, options);
    }

    $post(options) {
        return window.jQuery.post(window.BuyMeCoffeeAdmin.ajaxurl, options);
    }

    $adminPost(options) {
        options.action = 'buy-me-coffee_admin_ajax';
        return window.jQuery.post(window.BuyMeCoffeeAdmin.ajaxurl, options);
    }

    $getJSON(options) {
        return window.jQuery.getJSON(window.BuyMeCoffeeAdmin.ajaxurl, options);
    }
    dateTimeFormat(date, format) {
        const dateString = (date === undefined) ? null : date;
        const dateObj = moment(dateString);
        return dateObj.isValid() ? dateObj.format(format) : null;
    }

    localDate(date) {
        return moment.utc(date).local();
    }

    longLocalDate(date) {
        return this.dateTimeFormat(
            date, 'ddd, DD MMM, YYYY'
        );
    }

    saveData(key, data) {
        let existingData = window.localStorage.getItem('__buymecoffee_data');

        if (!existingData) {
            existingData = {};
        } else {
            existingData = JSON.parse(existingData);
        }

        existingData[key] = data;

        window.localStorage.setItem('__buymecoffee_data', JSON.stringify(existingData));
    }

    getData(key, defaultValue = false) {
        let existingData = window.localStorage.getItem('__buymecoffee_data');
        existingData = JSON.parse(existingData);
        if (!existingData) {
            return defaultValue;
        }

        if (existingData[key]) {
            return existingData[key];
        }

        return defaultValue;

    }

    longLocalDateTime(date) {
        return this.dateTimeFormat(
            date, 'ddd, DD MMM, YYYY hh:mm:ssa'
        );
    }

    ucFirst(text) {
        return text[0].toUpperCase() + text.slice(1).toLowerCase();
    }

    ucWords(text) {
        return (text + '').replace(/^(.)|\s+(.)/g, function ($1) {
            return $1.toUpperCase();
        })
    }

    slugify(text) {
        return text.toString().toLowerCase()
            .replace(/\s+/g, '-') // Replace spaces with -
            .replace(/[^\w\\-]+/g, '') // Remove all non-word chars
            .replace(/\\-\\-+/g, '-') // Replace multiple - with single -
            .replace(/^-+/, '') // Trim - from start of text
            .replace(/-+$/, ''); // Trim - from end of text
    }

    handleSuccess(message) {
        ElNotification({
            type: 'success',
            title: 'Success',
            message: message,
            offset: 32,
            dangerouslyUseHTMLString: true
        });
    }

    handleError(response) {
        if (response.responseJSON) {
            response = response.responseJSON;
        }
        let errorMessage = '';
        if (typeof response === 'string') {
            errorMessage = response;
        } else if (response && response.message) {
            errorMessage = response.message;
        } else {
            errorMessage = this.convertToText(response);
        }
        if (!errorMessage) {
            errorMessage = 'Something is wrong!';
        }
        this.$notify({
            type: 'error',
            title: 'Error',
            message: errorMessage,
            offset: 32,
            dangerouslyUseHTMLString: true
        });
    }

    convertToText(obj) {
        const string = [];
        if (typeof (obj) === 'object' && (obj.join === undefined)) {
            for (const prop in obj) {
                string.push(this.convertToText(obj[prop]));
            }
        } else if (typeof (obj) === 'object' && !(obj.join === undefined)) {
            for (const prop in obj) {
                string.push(this.convertToText(obj[prop]));
            }
        } else if (typeof (obj) === 'function') {

        } else if (typeof (obj) === 'string') {
            string.push(obj)
        }

        return string.join('<br />')
    }

    humanDiffTime(date) {
        const dateString = (date === undefined) ? null : date;
        if (!dateString) {
            return '';
        }
        const endTime = new Date();
        const timeDiff = endTime - appStartTime; // in ms
        const dateObj = moment(dateString);
        return dateObj.from(moment(window.BuyMeCoffeeAdmin.server_time).add(timeDiff, 'milliseconds'));
    }

    waitingTime(time1, time2) {
        if (!time2 || !time1) {
            return '';
        }
        time1 = moment(time1);
        time2 = moment(time2);
        return time2.from(time1);
    }
}
