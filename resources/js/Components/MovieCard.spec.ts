/**
 * @vitest-environment jsdom
 */
import type { Movie } from '@/types/models';

import messages from '@intlify/unplugin-vue-i18n/messages';
import { mount, renderToString } from '@vue/test-utils';
import { axe, toHaveNoViolations } from 'jest-axe';
import { describe, expect, it } from 'vitest';
import { createI18n } from 'vue-i18n';

import MovieCard from './MovieCard.vue';

expect.extend(toHaveNoViolations);

const movie: Movie = {
    id: 1,
    title: {
        'en-US': 'Movie Title',
        'ja-JP': '映画タイトル',
    },
    barcode: '1234567890123',
    release_date: '2021-01-01',
    length: 120,
    studio_id: 1,
    type_id: 1,
    slug: 'abc-123',
    created_at: '2021-01-01 00:00:00',
    updated_at: '2021-01-01 00:00:00',
    deleted_at: null,
    translations: {},
    reports: [],
    models: [],
    external_links: {},
    media: [],
    in_collection: false,
    in_favorites: false,
    in_wishlist: false,
    love_reactant_id: 1,
    love_reactant: {
        id: 1,
        reaction_counters: [],
        reactions: [],
        reaction_total: {
            count: 0,
            id: 1,
            weight: 1,
            reactant_id: 1,
            created_at: '2021-01-01 00:00:00',
            updated_at: '2021-01-01 00:00:00',
        },
        type: 'movie',
        created_at: '2021-01-01 00:00:00',
        updated_at: '2021-01-01 00:00:00',
    },
    studio: {
        id: 1,
        name: {
            'en-US': 'Studio Name',
            'ja-JP': 'スタジオ名',
        },
        founded_date: '2021-01-01',
        slug: 'studio-name',
        created_at: '2021-01-01 00:00:00',
        updated_at: '2021-01-01 00:00:00',
        deleted_at: null,
        translations: {},
        external_links: {},
        movies: {
            current_page: 1,
            data: [],
            first_page_url: '',
            from: 1,
            last_page: 1,
            last_page_url: '',
            links: [],
            next_page_url: null,
            path: '',
            per_page: 15,
            prev_page_url: null,
            to: 1,
            total: 1,
        },
        media: [],
    },
    type: {
        id: 1,
        name: 'Movie Type',
        created_at: '2021-01-01 00:00:00',
        updated_at: '2021-01-01 00:00:00',
        movies: [],
    },
    is_3d: false,
    is_vr: false,
    dmm_id: null,
    fanza_id: null,
    fc2_id: null,
    google_id: null,
    imdb_id: null,
    mgstage_id: null,
    tmdb_id: null,
    wikidata_id: null,
    fanart: null,
    poster: null,
    series: undefined,
};

const i18n = createI18n({
    legacy: false,
    locale: 'en-US',
    fallbackLocale: 'en-US',
    messages: messages,
});

describe('MovieCard', () => {
    it('renders properly in SSR mode', async () => {
        const contents = await renderToString(MovieCard, {
            global: {
                plugins: [i18n],
            },
            props: {
                movie,
            },
        });

        expect(contents).toMatchSnapshot();
    });

    it('has no a11y violations', async () => {
        const wrapper = mount(MovieCard, {
            global: {
                mocks: {
                    $route: vi.fn(),
                },
                plugins: [i18n],
            },
            props: {
                movie,
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
