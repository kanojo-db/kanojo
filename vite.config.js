/* eslint-env node */
import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import { quasar } from '@quasar/vite-plugin';
import { sentryVitePlugin } from '@sentry/vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import { dirname, resolve } from 'node:path';
import path from 'path';
import DefineOptions from 'unplugin-vue-define-options/vite';
import { fileURLToPath } from 'url';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        build: {
            sourcemap: true,
        },
        resolve: {
            alias: {
                'ziggy-js': path.resolve(
                    'vendor/tightenco/ziggy/dist/index.m.js',
                ),
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
                input: 'resources/js/app.ts',
                ssr: 'resources/js/ssr.ts',
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
            command === 'build'
                ? sentryVitePlugin({
                      org: 'kanojo',
                      project: 'javascript-vue',
                      include: './dist',
                      authToken: env.SENTRY_AUTH_TOKEN,
                  })
                : null,
        ],
    };
});
