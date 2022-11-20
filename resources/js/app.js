import "quasar/src/css/index.sass";

import "@quasar/extras/roboto-font-latin-ext/roboto-font-latin-ext.css";
import "@quasar/extras/mdi-v6/mdi-v6.css";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy/dist/vue.m";
import { Quasar, Dialog } from "quasar";
import quasarIconSet from "quasar/icon-set/svg-mdi-v6";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            // @ts-ignore
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            // @ts-ignore
            .use(ZiggyVue, Ziggy)
            .use(Quasar, {
                plugins: { Dialog },
                iconSet: quasarIconSet,
            }).mount(el);
    },
});

InertiaProgress.init({ color: "#69306D", includeCSS: true });
