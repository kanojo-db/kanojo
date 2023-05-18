import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Dialog, Notify, Quasar } from 'quasar';
import quasarIconSet from 'quasar/icon-set/svg-mdi-v6';
import { DefineComponent, createSSRApp, h } from 'vue';
import { createI18n } from 'vue-i18n';
import route from 'ziggy-js';

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
            const Ziggy = {
                ...props.initialPage.props.ziggy,
                location: new URL(props.initialPage.url),
            };

            const i18n = createI18n({
                legacy: false,
                locale: props.initialPage.props.locale as string,
                fallbackLocale: 'en-US',
                messages: localeMessages,
            });

            const ssrApp = createSSRApp({ render: () => h(App, props) })
                .use(plugin)
                .use(i18n)
                .mixin({
                    methods: {
                        route: (name, params, absolute, config = Ziggy) =>
                            route(name, params, absolute, config),
                    },
                });
            /*.use(Quasar, {
                    plugins: {},
                    iconSet: quasarIconSet,
                })*/

            return ssrApp;
        },
    }),
);
