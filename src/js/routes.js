import Dashboard from './Components/Dashboard.vue';
import Settings from './Components/Settings.vue';
import Supporters from './Components/Supports.vue';


export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            active: 'dashboard'
        }
    },
    {
        path: '/settings',
        name: 'settings',
        component: Settings
    },
    {
        path: '/supporters',
        name: 'supporters',
        component: Supporters
    }
];
