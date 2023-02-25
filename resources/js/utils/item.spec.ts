import { mount } from '@vue/test-utils';
import { useI18n } from 'vue-i18n';

import { getTitle, useTitle } from './item';

jest.mock('vue-i18n', () => ({
    useI18n: jest.fn(() => ({ locale: { value: 'en-US' } })),
}));

describe('getTitle', () => {
    test('returns the localized title', () => {
        const movie = {
            title: {
                'en-US': 'The Matrix',
                'ja-JP': 'マトリックス',
            },
        };

        const wrapper = mount({
            template: '<div>{{ title }}</div>',
            setup() {
                const title = getTitle(movie);

                return {
                    title,
                };
            },
        });

        expect(wrapper.text()).toBe('The Matrix');
        expect(useI18n).toHaveBeenCalledTimes(1);
    });

    test('returns the Japanese title if the localized title is missing', () => {
        const movie = {
            title: {
                'ja-JP': 'マトリックス',
            },
        };

        const wrapper = mount({
            template: '<div>{{ title }}</div>',
            setup() {
                const title = getTitle(movie);

                return {
                    title,
                };
            },
        });

        expect(wrapper.text()).toBe('マトリックス');
        expect(useI18n).toHaveBeenCalledTimes(1);
    });
});

describe('useTitle', () => {
    test('returns the computed title', async () => {
        const movie = {
            title: {
                'en-US': 'The Matrix',
                'ja-JP': 'マトリックス',
            },
        };

        const Component = {
            template: '<div>{{ title }}</div>',
            setup() {
                const title = useTitle(movie);

                return {
                    title,
                };
            },
        };

        const wrapper = mount(Component);

        expect(wrapper.text()).toBe('The Matrix');
    });

    test('returns the Japanese title if the localized title is missing', async () => {
        const movie = {
            title: {
                'ja-JP': 'マトリックス',
            },
        };

        const Component = {
            template: '<div>{{ title }}</div>',
            setup() {
                const title = useTitle(movie);

                return {
                    title,
                };
            },
        };

        const wrapper = mount(Component);

        expect(wrapper.text()).toBe('マトリックス');
    });
});
