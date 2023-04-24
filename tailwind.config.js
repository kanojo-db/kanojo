/* eslint-env node */
/** @type {import('tailwindcss').Config} */

export default {
    mode: 'jit',
    content: [
        './resources/js/**/*.{js,jsx,ts,tsx,vue}',
        './resources/views/**/*.blade.php',
    ],
    darkMode: 'class',
    theme: {
        extend: {
            gridTemplateColumns: {
                'auto-fill-100': 'repeat(auto-fill, 100px)',
                'auto-fill-200': 'repeat(auto-fill, 200px)',
                'auto-fill-256': 'repeat(auto-fill, 256px)',
                'auto-fill-384': 'repeat(auto-fill, 384px)',
            },
            colors: {
                primary: '#6a306d',
                secondary: '#E75A7C',
            },
        },
        screens: {
            xs: { min: '0px' },
            sm: { min: '600px' },
            md: { min: '960px' },
            lg: { min: '1280px' },
            xl: { min: '1920px' },
            xxl: { min: '2560px' },
        },
    },
    plugins: [require('@tailwindcss/typography')],
};
