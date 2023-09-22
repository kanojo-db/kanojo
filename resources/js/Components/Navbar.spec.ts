/**
 * @vitest-environment jsdom
 */
import { createTestingPinia } from '@pinia/testing';
import { mount, renderToString } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';
import { describe, expect, it } from 'vitest';

import Navbar from '@/Components/Navbar.vue';
import getI18nPlugin from '@/plugins/i18n';
import getVuetifyPlugin from '@/plugins/vuetify';
import { ziggyProps } from '@/test/ziggy.mock';

expect.extend(toHaveNoViolations);

const i18n = getI18nPlugin('en-US');
const vuetify = getVuetifyPlugin(i18n);

vi.mock('@inertiajs/vue3', async () => {
    const mod =
        await vi.importActual<typeof import('@inertiajs/vue3')>(
            '@inertiajs/vue3',
        );

    return {
        ...mod,
        usePage: () => ({
            props: {
                value: {
                    ...ziggyProps,
                },
            },
        }),
    };
});

describe('Navbar', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(
            {
                template: '<v-app><v-layout><navbar /></v-layout></v-app>',
                components: {
                    Navbar,
                },
            },
            {
                global: {
                    plugins: [i18n, vuetify, createTestingPinia()],
                    mocks: {
                        page: {
                            props: {
                                ...ziggyProps,
                            },
                        },
                        $page: {
                            component: 'Welcome',
                        },
                    },
                },
            },
        );

        // Expect renderToString to return a string
        expect(typeof contents).toBe('string');
        // Expect renderToString to not throw any errors
        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = mount(
            {
                template: '<v-app><v-layout><navbar /></v-layout></v-app>',
                components: {
                    Navbar,
                },
            },
            {
                global: {
                    mocks: {
                        $route: vi.fn(),
                        page: {
                            props: {
                                ...ziggyProps,
                            },
                        },
                        $page: {
                            component: 'Welcome',
                        },
                    },
                    plugins: [i18n, vuetify, createTestingPinia()],
                },
            },
        );

        const results = await axe(wrapper.html());

        expect(results).toHaveNoViolations();
    });
});
