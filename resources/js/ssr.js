import { createSSRApp, h } from "vue";
import { renderToString } from "@vue/server-renderer";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import createServer from "@inertiajs/server";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";

import { Quasar } from "quasar";
import quasarIconSet from "quasar/icon-set/svg-mdi-v6";

const appName = "Laravel";

createServer((page) =>
    createInertiaApp({
        page,
        render: renderToString,
        title: (title) => `${title} - ${appName}`,
        resolve: (name) =>
            resolvePageComponent(
                `./Pages/${name}.vue`,
                // @ts-ignore
                import.meta.glob("./Pages/**/*.vue")
            ),
        setup({ app, props, plugin }) {
            return createSSRApp({ render: () => h(app, props) })
                .use(plugin)
                .use(ZiggyVue, {
                    // @ts-ignore
                    ...page.props.ziggy,
                    // @ts-ignore
                    location: new URL(page.props.ziggy.location),
                })
                .use(Quasar, {
                    plugins: {},
                    iconSet: quasarIconSet,
                });
        },
    })
);
