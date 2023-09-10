/* eslint-env node */
import path from 'path';

import VueI18nPlugin from '@intlify/unplugin-vue-i18n/vite';
import { sentryVitePlugin } from '@sentry/vite-plugin';
import vue from '@vitejs/plugin-vue';
import dotenv from 'dotenv';
import laravel from 'laravel-vite-plugin';
import { FileSystemIconLoader } from 'unplugin-icons/loaders';
import Icons from 'unplugin-icons/vite';
import DefineOptions from 'unplugin-vue-define-options/vite';
import { defineConfig } from 'vite';
import vuetify, { transformAssetUrls } from 'vite-plugin-vuetify';
import { configDefaults } from 'vitest/config';

dotenv.config();

export default defineConfig(({ mode, ssrBuild }) => {
    return {
        build: {
            sourcemap: true,
            ssr: ssrBuild,
            cssCodeSplit: false,
        },
        ssr: {
            noExternal: [
                '@inertiajs/vue3/server',
                /\.css$/,
                /\?vue&type=style/,
                /^vuetify/,
                'vue-i18n',
            ],
        },
        server: {
            hmr: {
                host: process.env.VITE_HMR_IP || 'localhost',
            },
        },
        test: {
            ...configDefaults,
            environment: 'jsdom',
            globals: true,
            include: [
                'resources/js/**/*.{test,spec}.{js,mjs,cjs,ts,mts,cts,jsx,tsx}',
            ],
            setupFiles: ['resources/js/vitestSetup.ts'],
            coverage: 'istanbul',
            reporter: ['text', 'lcov'],
        },
        plugins: [
            laravel({
                input: ['resources/js/app.ts'],
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
            VueI18nPlugin({
                include: [path.resolve(__dirname, './resources/js/locales/**')],
                esm: ssrBuild,
            }),
            Icons({
                compiler: 'vue3',
                customCollections: {
                    custom: FileSystemIconLoader('./resources/icons', (svg) =>
                        svg.replace(/<svg/, '<svg fill="currentColor"'),
                    ),
                },
            }),
            DefineOptions(),
            vuetify({
                autoImport: true,
                ssr: ssrBuild,
                styles: {
                    configFile: path.resolve(
                        __dirname,
                        './resources/css/vuetify.scss',
                    ),
                },
            }),
            sentryVitePlugin({
                org: 'kanojo',
                project: 'javascript-vue',
                include: './dist',
                authToken: process.env.SENTRY_AUTH_TOKEN,
                telemetry: false,
            }),
        ],
    };
});
