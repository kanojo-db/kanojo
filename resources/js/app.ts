import type { DefineComponent } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';
import * as Sentry from '@sentry/vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';

import getI18nPlugin from '@/plugins/i18n';
import link from '@/plugins/link';
import pinia from '@/plugins/pinia';
import getVuetifyPlugin from '@/plugins/vuetify';

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.es';

import 'vuetify/styles/main.sass';
import '../css/app.scss';
import '../css/vuetify.scss';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/mousewheel';
import 'swiper/css/scrollbar';

const appName =
    window.document.getElementsByTagName('title')[0]?.innerText || 'Kanojo';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent<DefineComponent>(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
        ),
    progress: { color: '#15B097', includeCSS: true },
    setup({ el, App, props, plugin }) {
        const i18n = getI18nPlugin(props.initialPage.props.locale as string);

        const vuetify = getVuetifyPlugin(i18n);

        const vueApp = createSSRApp({ render: () => h(App, props) })
            .use(ZiggyVue, Ziggy)
            .use(pinia)
            .use(i18n)
            .use(vuetify)
            .use(link)
            .use(plugin);

        if (import.meta.env.PROD) {
            Sentry.init({
                app: vueApp,
                dsn: 'https://5fce5990e0e6417e8855d803341140cd@o4504320317259776.ingest.sentry.io/4504320373620736',
                integrations: [
                    new Sentry.BrowserTracing({
                        tracePropagationTargets: [
                            'localhost',
                            'kanojodb.com',
                            /^\//,
                        ],
                    }),
                ],
                tracesSampleRate: 0.1,
                sampleRate: 0.1,
                logErrors: true,
            });
        }

        vueApp.mount(el);

        return vueApp;
    },
});
