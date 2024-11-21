import Dashboard from './Components/Dashboard.vue';
import Settings from './Components/Settings.vue';
import PayPal from './Components/PayPal.vue';
import Stripe from './Components/Stripe.vue';
import Gateway from './Components/Gateway.vue';
import Supporter from "./Components/Supporter.vue";
import Onboarding from './Components/Onboarding.vue';
import Supporters from './Components/Supporters.vue';
import Notifications from './Components/Notifications.vue'
import Emails from "./Components/Email/Emails.vue";
import Webhook from "./Components/Webhook.vue";

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
        path: '/supporters',
        name: 'Supporters',
        component: Supporters,
        meta: {
            active: 'supporters'
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
        path: '/notifications',
        name: 'Notifications',
        component: Notifications,
        exact: true,
        children: [
            {
                path: '/email',
                name: 'Emails',
                component: Emails,
                exact: true
            },
            {
                path: '/webhook',
                name: 'Webhook',
                component: Webhook,
                exact: true
            }
        ]
    },
    {
        path: '/gateway',
        name: 'Gateway',
        component: Gateway,
        exact: true,
        children: [
            {
                path: '/paypal',
                name: 'paypal',
                component: PayPal,
                exact: true
            },
            {
                path: '/stripe',
                name: 'stripe',
                component: Stripe,
                exact: true
            },
        ]
    },
    {
        path: '/quick-setup',
        name: 'Onboarding',
        component: Onboarding,
        exact: true
    }
];
