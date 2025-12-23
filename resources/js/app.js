import './bootstrap';
import '../css/app.css';

import {createApp, h} from 'vue';
import {createInertiaApp} from '@inertiajs/vue3';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {ZiggyVue} from 'ziggy-js';
import {createPinia} from "pinia";
import PrimeVue from 'primevue/config';
import Nora from '@primevue/themes/nora'
import ToastService from 'primevue/toastservice'
import 'primeicons/primeicons.css';

document.documentElement.classList.toggle('my-app-dark');

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, App, props, plugin}) {
        const app = createApp({render: () => h(App, props)});

        const primeVueOptions = {
            theme: {
                preset: Nora,
                options: {
                    darkModeSelector: '.my-app-dark',
                },
            },
            ripple: true,
        };

        return app
            .use(plugin)
            .use(PrimeVue, primeVueOptions)
            .use(createPinia())
            .use(
                ZiggyVue,
            )
            .use(ToastService)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
}).then();
