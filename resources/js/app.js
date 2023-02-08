import { createInertiaApp } from '@inertiajs/vue3';
import '@quasar/extras/mdi-v6/mdi-v6.css';
import '@quasar/extras/roboto-font-latin-ext/roboto-font-latin-ext.css';
import { BrowserTracing } from '@sentry/tracing';
import * as Sentry from '@sentry/vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Dialog, Notify, Quasar } from 'quasar';
import quasarIconSet from 'quasar/icon-set/svg-mdi-v6';
import 'quasar/src/css/index.sass';
import { createApp, h } from 'vue';
import { createI18n } from 'vue-i18n';

import '../../public/vendor/cookie-consent/css/cookie-consent.css';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';
import '../css/app.scss';
import localeMessages from './vue-i18n-locales.generated';

const appName =
    window.document.getElementsByTagName('title')[0]?.innerText || 'Kanojo';

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    progress: { color: '#69306D', includeCSS: true },
    setup({ el, App, props, plugin }) {
        const i18n = createI18n({
            legacy: false,
            locale: props.initialPage.props.locale,
            fallbackLocale: 'en-US',
            messages: localeMessages,
        });

        const vueApp = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(i18n)
            .use(ZiggyVue, Ziggy)
            .use(Quasar, {
                plugins: { Dialog, Notify },
                iconSet: quasarIconSet,
            });

        Sentry.init({
            app: vueApp,
            dsn: 'https://5fce5990e0e6417e8855d803341140cd@o4504320317259776.ingest.sentry.io/4504320373620736',
            integrations: [
                new BrowserTracing({
                    tracePropagationTargets: [
                        'localhost',
                        'my-site-url.com',
                        /^\//,
                    ],
                }),
                new Sentry.Replay(),
            ],
            tracesSampleRate: 0.1,
            replaysOnErrorSampleRate: 1.0,
            logErrors: true,
        });

        vueApp.mount(el);

        return App;
    },
});
