import { createTestingPinia } from '@pinia/testing';
import { renderToString, shallowMount } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';

import Footer from '@/Components/Footer.vue';
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

describe('BtnDropdown', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(
            {
                template: '<v-app><v-layout><Footer /></v-layout></v-app>',
                components: {
                    Footer,
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

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = shallowMount(Footer);

        expect(await axe(wrapper.html())).toHaveNoViolations();
    });
});
