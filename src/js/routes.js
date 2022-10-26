import Dashboard from './Components/Dashboard.vue';
// import Settings from './Components/Settings';
// import Supporters from './Components/Supports';


export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: {
            active: 'dashboard'
        }
    },
    // {
    //     path: '/settings',
    //     name: 'settings',
    //     component: Settings
    // },
    // {
    //     path: '/supporters',
    //     name: 'supporters',
    //     component: Supporters
    // }
];
