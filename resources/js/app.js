import './bootstrap';
import '../css/app.css';
import '@fontsource/roboto';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

import PrimeVue from 'primevue/config';
import "../theme/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import moment from 'moment';
import 'moment/dist/locale/ca';

import ToastService from 'primevue/toastservice';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';
import caPrimeVueLocale from '../theme/ca.json';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        var myApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(PrimeVue, {locale: caPrimeVueLocale.ca})
            .use(ToastService);
        moment.locale('ca');
        myApp.config.globalProperties.$moment = moment;
        myApp.mount(el);
        return myApp;
    },
    progress: {
        delay: 0,
        color: '#ffffff',
        includeCSS: true,
        showSpinner: true,
    },
});
