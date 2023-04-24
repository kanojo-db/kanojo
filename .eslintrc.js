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
        'plugin:tailwindcss/recommended',
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
        // Make sure we don't ship logs to production
        'no-console': 'error',
        // Ensure the SFC blocks are in the correct order
        'vue/component-tags-order': [
            'error',
            {
                order: ['script', 'template', 'style'],
            },
        ],
        // We sometimes use classes that are not defined by Tailwind
        'tailwindcss/no-custom-classname': 'off',
        // Ensure all components use the proper languages
        'vue/block-lang': [
            'error',
            {
                script: {
                    lang: 'ts',
                },
                template: {
                    lang: 'html',
                },
                style: {
                    lang: 'scss',
                },
            },
        ],
        // Ensure all components use script setup
        'vue/component-api-style': ['error', ['script-setup']],
        // Ensure custom events use the proper casing
        'vue/custom-event-name-casing': ['error', 'camelCase'],
        // Ensure all defineEmits are type-based
        'vue/define-emits-declaration': ['error', 'type-based'],
        // Ensure the order of compiler macros
        'vue/define-macros-order': [
            'error',
            {
                order: [
                    'defineProps',
                    'defineEmits',
                    'defineOptions',
                    'defineSlots',
                ],
            },
        ],
        // Ensure defineProps uses runtime-based declaration
        'vue/define-props-declaration': ['error', 'runtime'],
        // Ensure we follow HTML spec for boolean attributes
        'vue/no-boolean-default': ['error'],
        // Make sure attributes are not inherited twice
        'vue/no-duplicate-attr-inheritance': ['error'],
        // Ensure we don't have empty blocks in SFCs
        'vue/no-empty-component-block': ['error'],
        // Make sure classes only get passed one object
        'vue/no-multiple-objects-in-class': ['error'],
        // Make sure we don't accidentally lose reactivity in refs
        'vue/no-ref-object-destructure': ['error'],
        // Ensure we use Vuetify components instead of HTML elements
        'vue/no-restricted-html-elements': [
            'error',
            {
                element: 'button',
                message: "Prefer use of Vuetify's <v-btn /> component instead",
            },
            {
                element: 'input',
                message:
                    "Prefer use of Vuetify's <v-text-field /> component instead",
            },
            {
                element: 'textarea',
                message:
                    "Prefer use of Vuetify's <v-textarea /> component instead",
            },
            {
                element: 'select',
                message:
                    "Prefer use of Vuetify's <v-select /> component instead",
            },
            {
                element: 'label',
                message:
                    "Prefer use of Vuetify's <v-label /> component instead",
            },
            {
                element: 'form',
                message: "Prefer use of Vuetify's <v-form /> component instead",
            },
            {
                element: 'table',
                message:
                    "Prefer use of Vuetify's <v-table /> component instead",
            },
            {
                element: 'hr',
                message:
                    "Prefer use of Vuetify's <v-divider /> component instead",
            },
        ],
        // Ensure we don't have default values in required props
        'vue/no-required-prop-with-default': ['error'],
        // Prevent root v-if, since components should be made conditional in the parent
        'vue/no-root-v-if': ['error'],
        // Avoid useless interpolation
        'vue/no-useless-mustaches': ['error'],
        // Separate blocks in SFCs with a newline
        'vue/padding-line-between-blocks': ['error', 'always'],
        // Pad between sibling tags for readability
        'vue/padding-line-between-tags': ['error'],
        // Prefer newer syntax for defining options
        'vue/prefer-define-options': 'error',
        // Avoid issues with props acceptiont booleans and strings
        'vue/prefer-prop-type-boolean-first': 'error',
        // Force splitting static and dynamic classes
        'vue/prefer-separate-static-class': 'error',
        // Use concise syntax for v-bind when true
        'vue/prefer-true-attribute-shorthand': 'error',
        // Strongly type props
        // 'vue/require-typed-object-prop': 'error',
        // Strongly type refs
        'vue/require-typed-ref': 'error',
        // Ensure all v-for use the same delimiter
        'vue/v-for-delimiter-style': 'error',
        // This rule is currently broken (2023-09-06)
        '@intlify/vue-i18n/no-missing-keys': 'off',
    },
    settings: {
        'vue-i18n': {
            // localeDir: './resources/js/locales/*.json',
            messageSyntaxVersion: '^9.0.0',
        },
    },
};
