import Dashboard from './Components/Dashboard.vue';
import Settings from './Components/Settings.vue';
import PayPal from './Components/PayPal.vue';
import Stripe from './Components/Stripe.vue';
import Gateway from './Components/Gateway.vue';
import Supporter from "./Components/Supporter.vue";
import Onboarding from './Components/Onboarding.vue'

export default [
    {
        path: '/',
        name: 'Dashboard',
        component: Dashboard,
        meta: {
            active: 'dashboard'
        }
    },
    {
        path: '/settings',
        name: 'Settings',
        component: Settings,
    },
    {
        path: '/supporter/:id',
        name: 'Supporter',
        component: Supporter,
    },
    {
        path: '/gateway',
        name: 'Gateway',
        component: Gateway,
        exact: true,
    },
    {
        path: '/gateway/paypal',
        name: 'paypal',
        component: PayPal,
        exact: true
    },
    {
        path: '/gateway/stripe',
        name: 'stripe',
        component: Stripe,
        exact: true
    },
    {
        path: '/quick-setup',
        name: 'Onboarding',
        component: Onboarding,
        exact: true
    }
];
