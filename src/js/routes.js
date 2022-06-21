import Dashboard from './Components/Dashboard';
import Settings from './Components/Settings';
import Supporters from './Components/Supports';

export const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
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
