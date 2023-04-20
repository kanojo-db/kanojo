/* eslint-env node */
/** @type {import('tailwindcss').Config} */

export default {
    content: ['./resources/js/**/*.vue', './resources/views/**/*.blade.php'],
    darkMode: 'class',
    theme: {
        extend: {},
    },
    plugins: [require('daisyui')],
};
