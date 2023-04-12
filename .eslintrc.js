/* eslint-env node */
module.exports = {
    env: {
        browser: true,
        es2021: true,
    },
    globals: {
        Ziggy: 'readonly',
        route: 'readonly',
        axios: 'readonly',
        defineOptions: 'readonly',
    },
    extends: [
        'eslint:recommended',
        'plugin:prettier/recommended',
        'plugin:vue/vue3-recommended',
        'plugin:@intlify/vue-i18n/recommended',
        'plugin:vuejs-accessibility/recommended',
        'plugin:@typescript-eslint/recommended',
    ],
    overrides: [
        {
            files: ['**/*.spec.{j,t}s?(x)'],
            env: {
                jest: true,
            },
            rules: {
                'jest/consistent-test-it': [
                    'error',
                    { fn: 'test', withinDescribe: 'it' },
                ],
                '@typescript-eslint/explicit-function-return-type': 'off',
            },
            plugins: ['jest', 'jest-formatting'],
            extends: [
                'plugin:jest/recommended',
                'plugin:jest/style',
                'plugin:jest-formatting/strict',
            ],
            settings: {
                jest: {
                    version: 29,
                },
            },
        },
    ],
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
        'vue/multi-word-component-names': 'off',
        'no-console': 'error',
    },
    settings: {
        'vue-i18n': {
            localeDir: './resources/lang/*.{json,yaml,yml}',
            messageSyntaxVersion: '^9.0.0',
        },
    },
};
