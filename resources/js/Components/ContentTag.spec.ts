import type { Tag } from '@/types/models';

import { renderToString, shallowMount } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';

import ContentTag from '@/Components/ContentTag.vue';
import getI18nPlugin from '@/plugins/i18n';
import getVuetifyPlugin from '@/plugins/vuetify';

expect.extend(toHaveNoViolations);

const i18n = getI18nPlugin('en-US');
const vuetify = getVuetifyPlugin(i18n);

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

describe('ContentTag', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(ContentTag, {
            propsData: {
                tag,
            },
            global: {
                plugins: [i18n, vuetify],
            },
        });

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = shallowMount(ContentTag, {
            propsData: {
                tag,
            },
            global: {
                plugins: [i18n, vuetify],
            },
        });

        const results = await axe(wrapper.html(), {
            rules: {
                region: { enabled: false },
            },
        });

        expect(results).toHaveNoViolations();
    });
});
