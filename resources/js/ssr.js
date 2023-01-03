import { createInertiaApp } from '@inertiajs/inertia-vue3';
import createServer from '@inertiajs/server';
import { renderToString } from '@vue/server-renderer';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { Quasar } from 'quasar';
import quasarIconSet from 'quasar/icon-set/svg-mdi-v6';
import { createSSRApp, h } from 'vue';

import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/vue.m';

const appName = 'Laravel';

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                import.meta.glob('./Pages/**/*.vue'),
            ),
        setup({ app, props, plugin }) {
            return createSSRApp({ render: () => h(app, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    ...page.props.ziggy,
                    location: new URL(page.props.ziggy.location),
                })
                .use(Quasar, {
                    plugins: {},
                    iconSet: quasarIconSet,
                });
        },
    }),
);
