import type { I18n } from 'vue-i18n';

import { useI18n } from 'vue-i18n';
import { createVuetify } from 'vuetify';
import { createVueI18nAdapter } from 'vuetify/locale/adapters/vue-i18n';

import MdiCheckboxBlankOutline from '~icons/mdi/checkbox-blank-outline?raw';
import MdiCheckboxIntermediate from '~icons/mdi/checkbox-intermediate?raw';
import MdiCheckboxOutline from '~icons/mdi/checkbox-outline?raw';
import MdiChevronDown from '~icons/mdi/chevron-down?raw';
import MdiCloseCircleOutline from '~icons/mdi/close-circle-outline?raw';
import MdiClose from '~icons/mdi/close?raw';

import { dark, light } from '@/themes';

function getVuetifyPlugin(i18n: I18n, ssr: boolean = false) {
    return createVuetify({
        ssr,
        defaults: {
            global: {
                ripple: false,
            },
            VBtn: {
                variant: 'flat',
            },
            VMenu: {
                openDelay: 50,
                closeDelay: 50,
                location: 'bottom center',
            },
            VDivider: {
                opacity: 0.12,
            },
            VAutocomplete: {
                clearIcon: MdiCloseCircleOutline,
                menuIcon: MdiChevronDown,
                variant: 'outlined',
            },
            VSelect: {
                clearIcon: MdiCloseCircleOutline,
                menuIcon: MdiChevronDown,
                variant: 'outlined',
            },
            VTextField: {
                clearIcon: MdiCloseCircleOutline,
                variant: 'outlined',
            },
            VCheckbox: {
                indeterminateIcon: MdiCheckboxIntermediate,
                falseIcon: MdiCheckboxBlankOutline,
                trueIcon: MdiCheckboxOutline,
            },
            VAlert: {
                closeIcon: MdiClose,
            },
        },
        locale: {
            adapter: createVueI18nAdapter({ i18n, useI18n }),
        },
        theme: {
            defaultTheme: 'light',
            themes: {
                light,
                dark,
            },
        },
    });
}

export default getVuetifyPlugin;
