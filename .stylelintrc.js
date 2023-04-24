/* eslint-env node */

module.exports = {
    extends: [
        'stylelint-config-standard-scss',
        'stylelint-config-sass-guidelines',
        'stylelint-prettier/recommended',
    ],
    customSyntax: 'postcss-scss',
    rules: {
        'scss/at-rule-no-unknown': [
            true,
            {
                ignoreAtRules: ['tailwind', 'apply', 'layer', 'config'],
            },
        ],
        'scss/function-no-unknown': [
            true,
            {
                ignoreFunctions: ['theme'],
            },
        ],
    },
    overrides: [
        {
            files: ['**/*.vue'],
            customSyntax: 'postcss-html',
        },
    ],
};
