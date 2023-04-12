import { config } from '@vue/test-utils';
import { cloneDeep } from 'lodash-es';
import Quasar from 'quasar';
import quasarIconSet from 'quasar/icon-set/svg-mdi-v6';
import { afterAll, beforeAll, vi } from 'vitest';
import { ref } from 'vue';
import { createI18n } from 'vue-i18n';

import localeMessages from '@/vue-i18n-locales.generated';

/**
 * Injections for Components with a QPage root Element
 */
export function qLayoutInjections() {
    return {
        // pageContainerKey
        _q_pc_: true,
        // layoutKey
        _q_l_: {
            header: { size: 0, offset: 0, space: false },
            right: { size: 300, offset: 0, space: false },
            footer: { size: 0, offset: 0, space: false },
            left: { size: 300, offset: 0, space: false },
            isContainer: ref(false),
            view: ref('lHh Lpr lff'),
            rows: ref({ top: 'lHh', middle: 'Lpr', bottom: 'lff' }),
            height: ref(900),
            instances: {},
            update: vi.fn(),
            animate: vi.fn(),
            totalWidth: ref(1200),
            scroll: ref({ position: 0, direction: 'up' }),
            scrollbarWidth: ref(125),
        },
    };
}

export function setupVue() {
    const originalConfig = cloneDeep(config.global);

    const i18n = createI18n({
        legacy: false,
        locale: 'en-US',
        fallbackLocale: 'en-US',
        messages: localeMessages,
    });

    beforeAll(() => {
        config.global.mocks = {
            route: (name: string, params: Record<string, string>) => {
                return (
                    '/' + name + '?' + new URLSearchParams(params).toString()
                );
            },
        };

        config.global.plugins.unshift([
            Quasar,
            {
                iconSet: quasarIconSet,
            },
        ]);

        config.global.plugins.unshift(i18n);

        config.global.provide = {
            ...config.global.provide,
            ...qLayoutInjections(),
        };
    });

    afterAll(() => {
        config.global = originalConfig;
    });
}
