/* eslint-env node */
import { quasar } from '@quasar/vite-plugin';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import { configDefaults, defineConfig } from 'vitest/config';

export default defineConfig({
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
    ],
    test: {
        ...configDefaults,
        globals: true,
        include: [
            'resources/js/**/*.{test,spec}.{js,mjs,cjs,ts,mts,cts,jsx,tsx}',
        ],
    },
    resolve: {
        alias: {
            '@ziggy-js': path.resolve('vendor/tightenco/ziggy/dist'),
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
});
