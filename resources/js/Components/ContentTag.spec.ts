import type { Tag } from '@/types/models';

import messages from '@intlify/unplugin-vue-i18n/messages';
import { renderToString, shallowMount } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';
import { createI18n } from 'vue-i18n';

import ContentTag from '@/Components/ContentTag.vue';

expect.extend(toHaveNoViolations);

const tag: Tag = {
    id: 1,
    name: {
        'en-US': 'Test Tag',
        'ja-JP': 'テストタグ',
    },
    type: 'type',
    slug: 'test-tag',
    order_column: 1,
    created_at: '2020-01-01 00:00:00',
    updated_at: '2020-01-01 00:00:00',
};

const i18n = createI18n({
    legacy: false,
    locale: 'en-US',
    fallbackLocale: 'en-US',
    messages: messages,
});

describe('BtnDropdown', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(ContentTag, {
            props: {
                tag,
            },
            global: {
                plugins: [i18n],
            },
        });

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = shallowMount(ContentTag, {
            props: {
                tag,
            },
            global: {
                plugins: [i18n],
            },
        });

        const results = await axe(wrapper.html(), {
            rules: {
                region: { enabled: false },
            },
        });

        expect(results).toHaveNoViolations();
    });

    it('renders the correct content', () => {
        const wrapper = shallowMount(ContentTag, {
            props: {
                tag,
            },
            global: {
                plugins: [i18n],
            },
        });

        expect(wrapper.text()).toContain(tag.name['en-US']);
    });
});
