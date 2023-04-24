import type { DefineComponent } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';
import createServer from '@inertiajs/vue3/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createSSRApp, h } from 'vue';
import route from 'ziggy-js';

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

import type { RouteParams, RouteParamsWithQueryOverload } from 'ziggy-js';

const appName = 'Kanojo';

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
            const i18n = getI18nPlugin(props.initialPage.props.locale);

            const vuetify = getVuetifyPlugin(i18n);

            const Ziggy = {
                ...props.initialPage.props.ziggy,
                location: new URL(props.initialPage.props.ziggy.url),
            };

            return createSSRApp({ render: () => h(App, props) })
                .use(ZiggyVue, Ziggy)
                .use(pinia)
                .use(i18n)
                .use(vuetify)
                .use(link)
                .use(plugin)
                .mixin({
                    methods: {
                        route: (
                            name: string | undefined,
                            params: RouteParamsWithQueryOverload | RouteParams,
                            absolute: boolean,
                            config = Ziggy,
                        ) => {
                            return route(name, params, absolute, config);
                        },
                    },
                });
        },
    }),
);
