module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    globals: {
        Ziggy: 'readonly',
        route: 'readonly',
        axios: 'readonly',
    },
    extends: [
        'eslint:recommended',
        'plugin:prettier/recommended',
        'plugin:vue/vue3-recommended',
        'plugin:@intlify/vue-i18n/recommended',
        'plugin:vuejs-accessibility/recommended',
        'plugin:@typescript-eslint/recommended',
    ],
    overrides: [],
    parser: 'vue-eslint-parser',
    parserOptions: {
        parser: '@typescript-eslint/parser',
        ecmaVersion: 'latest',
        sourceType: 'module',
    },
    plugins: ['vue', '@typescript-eslint'],
    rules: {
        // Prettier conflicts
        'vue/html-indent': 'off',
        'vue/singleline-html-element-content-newline': 'off',
        'vue/html-self-closing': [
            'error',
            {
                html: {
                    void: 'always',
                    normal: 'always',
                    component: 'always',
                },
                svg: 'always',
                math: 'always',
            },
        ],
    },
    settings: {
        'vue-i18n': {
            localeDir: './resources/lang/*.{json,yaml,yml}',
            messageSyntaxVersion: '^9.0.0',
        },
    },
};
