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
    jQuery('.buymecoffee_menu_item').removeClass('active');
    let active = to.meta.active;
    if(active) {
        jQuery('.buymecoffee_main-menu-items').find('li[data-key='+active+']').addClass('active');
    }
});
