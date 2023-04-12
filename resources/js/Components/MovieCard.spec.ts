/**
 * @vitest-environment jsdom
 */
import { shallowMount } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';
import { describe, expect, it } from 'vitest';

import { setupVue } from '@/test/setup';

import MovieCard from './MovieCard.vue';

expect.extend(toHaveNoViolations);

setupVue();

describe('MovieCard', () => {
    it('has no a11y violations', async () => {
        const wrapper = shallowMount(MovieCard, {
            props: {
                movie: {
                    id: 1,
                    title: {
                        'en-US': 'Movie Title',
                        'ja-JP': '映画タイトル',
                    },
                    product_code: 'ABC-123',
                    release_date: '2021-01-01',
                    slug: 'abc-123',
                },
            },
        });

        expect(await axe(wrapper.html())).toHaveNoViolations();
    });

    it('displays the movie title', () => {
        const wrapper = shallowMount(MovieCard, {
            props: {
                movie: {
                    id: 1,
                    title: {
                        'en-US': 'Movie Title',
                        'ja-JP': '映画タイトル',
                    },
                    product_code: 'ABC-123',
                    release_date: '2021-01-01',
                    slug: 'abc-123',
                },
            },
        });

        expect(wrapper.find()).toBe('Movie Title');
    });
});
