import { shallowMount } from '@vue/test-utils';
import { useI18n } from 'vue-i18n';

import { Movie } from '@/types/models';

import { getName, getTitle, useName, useTitle } from './item';

jest.mock('vue-i18n', () => ({
    useI18n: jest.fn(() => ({ locale: { value: 'en-US' } })),
}));

describe('getTitle', () => {
    it('returns the localized title', () => {
        const movie = {
            title: {
                'en-US': 'The Matrix',
                'ja-JP': 'マトリックス',
            },
        } as unknown as Movie;

        const wrapper = shallowMount({
            template: '<div>{{ title }}</div>',
            setup() {
                const title = getTitle(movie, 'en-US');

                return {
                    title,
                };
            },
        });

        expect(wrapper.text()).toBe('The Matrix');
        expect(useI18n).toHaveBeenCalledTimes(1);
    });

    it('returns the Japanese title if the localized title is missing', () => {
        const movie = {
            title: {
                'ja-JP': 'マトリックス',
            },
        } as unknown as Movie;

        const wrapper = shallowMount({
            template: '<div>{{ title }}</div>',
            setup() {
                const title = getTitle(movie, 'en-US');

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
    it('returns the computed title', async () => {
        const movie = {
            title: {
                'en-US': 'The Matrix',
                'ja-JP': 'マトリックス',
            },
        } as unknown as Movie;

        const Component = {
            template: '<div>{{ title }}</div>',
            setup() {
                const title = useTitle(movie, 'en-US');

                return {
                    title,
                };
            },
        };

        const wrapper = shallowMount(Component);

        expect(wrapper.text()).toBe('The Matrix');
    });

    it('returns the Japanese title if the localized title is missing', async () => {
        const movie = {
            title: {
                'ja-JP': 'マトリックス',
            },
        } as unknown as Movie;

        const Component = {
            template: '<div>{{ title }}</div>',
            setup() {
                const title = useTitle(movie, 'en-US');

                return {
                    title,
                };
            },
        };

        const wrapper = shallowMount(Component);

        expect(wrapper.text()).toBe('マトリックス');
    });
});

describe('getName', () => {
    it('returns the name in the correct language', () => {
        const item = {
            name: {
                'en-US': 'English Name',
                'es-ES': 'Nombre en Español',
                'ja-JP': '日本語の名前',
            },
        };
        const locale = 'es';

        const mockUseI18n = {
            locale: {
                value: locale,
            },
        };

        const result = getName(item, 'en-US');

        expect(result).toBe(item.name[locale]);
    });

    it('returns the Japanese name if no localized name exists', () => {
        const item = {
            name: {
                'en-US': 'English Name',
                'fr-FR': 'Nom en français',
            },
        };
        const locale = 'es';

        const mockUseI18n = {
            locale: {
                value: locale,
            },
        };

        const result = getName(item, 'en-US');

        expect(result).toBe(item.name['ja-JP']);
    });
});
