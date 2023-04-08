import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import { quasar } from '@quasar/vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { dirname, resolve } from 'node:path';
import path from 'path';
import DefineOptions from 'unplugin-vue-define-options/vite';
import { fileURLToPath } from 'url';
import { defineConfig } from 'vite';
import checker from 'vite-plugin-checker';

export default defineConfig({
    resolve: {
        alias: {
            '@ziggy-js': path.resolve('vendor/tightenco/ziggy/dist'),
        },
    },
    ssr: {
        noExternal: ['@inertiajs/server'],
    },
    server: {
        hmr: {
            host: '192.168.0.44',
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
        DefineOptions(),
        checker({
            overlay: true,
            vueTsc: true,
            eslint: {
                lintCommand: 'eslint --ext .js,.ts,.vue resources/js',
            },
        }),
    ],
});
