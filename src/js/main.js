import routes from './routes';
import { createWebHashHistory, createRouter } from 'vue-router'
import BuyMeCoffee from './plugin_main_js_file.js';

const router = createRouter({
    history: createWebHashHistory(),
    routes
});


const framework = new BuyMeCoffee();

framework.app.config.globalProperties.appVars = window.BuyMeCoffeeAdmin;

window.BuyMeCoffeeApp = framework.app.use(router).mount('#buy-me-coffee_app');

router.afterEach((to, from) => {
    const gateways = ["stripe", "paypal"];
    jQuery('.buymecoffee_app_menu li').removeClass('active');
    let active = to.meta.active;
    if (to.name) {
        active = to.name.toLowerCase();
        if (gateways.includes(active)) {
            jQuery('.buymecoffee_gateway_item div').removeClass('active');
            jQuery('.buymecoffee_gateway_item .buymecoffee_gateway_' + active).addClass('active');
            active = 'gateway';
        }
        let selector = '.buymecoffee_menu_' + active;
        jQuery('.buymecoffee_app_menu ' + selector).addClass('active');
    }
});

jQuery('.update-nag,.notice, #wpbody-content > .updated, #wpbody-content > .error').remove();

