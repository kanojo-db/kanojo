import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import { quasar } from '@quasar/vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { dirname, resolve } from 'node:path';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';

export default defineConfig({
    ssr: {
        noExternal: ['@inertiajs/server'],
    },
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            ssr: 'resources/js/ssr.js',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        quasar({
            sassVariables: 'resources/css/variables.sass',
        }),
        VueI18nPlugin({
            /* options */
            // locale messages resource pre-compile option
            include: resolve(
                dirname(fileURLToPath(import.meta.url)),
                './resources/js/vue-i18n-locales.generated.js',
            ),
        }),
    ],
});
