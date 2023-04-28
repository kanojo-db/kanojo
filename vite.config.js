/* eslint-env node */
import { quasar } from '@quasar/vite-plugin';
import { sentryVitePlugin } from '@sentry/vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import DefineOptions from 'unplugin-vue-define-options/vite';
import { defineConfig, loadEnv } from 'vite';

export default defineConfig(({ command, mode, ssrBuild }) => {
    const env = loadEnv(mode, process.cwd(), '');

    return {
        build: {
            sourcemap: true,
            ssr: ssrBuild,
        },
        resolve: {
            alias: {
                'ziggy-js': path.resolve(
                    'vendor/tightenco/ziggy/dist/index.m.js',
                ),
            },
        },
        ssr: {
            noExternal: ['@inertiajs/vue3/server'],
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
                isProduction: mode === 'production',
            }),
            quasar({
                sassVariables: 'resources/css/variables.sass',
                runMode: ssrBuild ? 'ssr-server' : 'ssr-server',
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
