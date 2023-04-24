/**
 * @vitest-environment jsdom
 */
import messages from '@intlify/unplugin-vue-i18n/messages';
import { mount, renderToString } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';
import { describe, expect, it } from 'vitest';
import { createI18n } from 'vue-i18n';

import Navbar from '@/Components/Navbar.vue';

expect.extend(toHaveNoViolations);

const i18n = createI18n({
    legacy: false,
    locale: 'en-US',
    fallbackLocale: 'en-US',
    messages: messages,
});

describe('MovieCard', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(Navbar, {
            global: {
                plugins: [i18n],
                mocks: {
                    page: {
                        props: {
                            user: null,
                        },
                    },
                },
            },
        });

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = mount(Navbar, {
            global: {
                mocks: {
                    $route: vi.fn(),
                    page: {
                        props: {
                            user: null,
                        },
                    },
                },
                plugins: [i18n],
            },
        });

        const results = await axe(wrapper.html());

        expect(results).toHaveNoViolations();
    });
});
