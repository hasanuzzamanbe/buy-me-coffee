window.buyMeCoffeeBus = new window.buyMeCoffee.Vue();

window.buyMeCoffee.Vue.mixin({
    methods: {
        applyFilters: window.buyMeCoffee.applyFilters,
        addFilter: window.buyMeCoffee.addFilter,
        addAction: window.buyMeCoffee.addFilter,
        doAction: window.buyMeCoffee.doAction,
        $get: window.buyMeCoffee.$get,
        $adminGet: window.buyMeCoffee.$adminGet,
        $adminPost: window.buyMeCoffee.$adminPost,
        $post: window.buyMeCoffee.$post,
        $publicAssets: window.buyMeCoffee.$publicAssets,
        $t(str) {
            let transString = buyMeCoffeeAdmin.i18n[str];
            if(transString) {
                return transString;
            }
            return str;
        }
    }
});

import {routes} from './routes';

const router = new window.buyMeCoffee.Router({
    routes: window.buyMeCoffee.applyFilters('buyMeCoffee_global_vue_routes', routes),
    linkActiveClass: 'active'
});

import App from './AdminApp';

new window.buyMeCoffee.Vue({
    el: '#buy-me-coffee_app',
    render: h => h(App),
    router: router
});

