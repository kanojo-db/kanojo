import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import * as Sentry from '@sentry/vue';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Dialog, Notify, Quasar } from 'quasar';
import quasarIconSet from 'quasar/icon-set/svg-mdi-v6';
import { DefineComponent, createSSRApp, h } from 'vue';
import { createI18n } from 'vue-i18n';

import localeMessages from '@/vue-i18n-locales.generated';

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => (title ? `${title} - ${appName}` : appName),
        resolve: (name) =>
            resolvePageComponent<DefineComponent>(
                `./Pages/${name}.vue`,
                import.meta.glob<DefineComponent>('./Pages/**/*.vue'),
            ),
        setup({ App, props, plugin }) {
            const i18n = createI18n({
                legacy: false,
                locale: props.initialPage.props.locale as string,
                fallbackLocale: 'en-US',
                messages: localeMessages,
            });

            const ssrApp = createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(i18n)
                .use(ZiggyVue, {
                    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                    // @ts-expect-error
                    ...page.props.ziggy,
                    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                    // @ts-expect-error
                    location: new URL(page.props.ziggy.location),
                });
            /*.use(Quasar, {
                    plugins: {},
                    iconSet: quasarIconSet,
                })*/

            Sentry.init({
                app: ssrApp,
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
                logErrors: true,
            });

            return ssrApp;
        },
    }),
);
