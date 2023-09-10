import type { Person } from '@/types/models';

import { DateTime } from 'luxon';

/**
 * Get the menu entries for the main navigation menu.
 *
 * @return {Array} The menu entries.
 */
export function getMenuEntries() {
    return [
        {
            title: 'Movies',
            items: [
                {
                    title: 'Recently released',
                    routeName: 'movies.index',
                    routeParams: {
                        'filter[recent]': import.meta.env.SSR
                            ? ''
                            : DateTime.now().toISODate(),
                    },
                },
                {
                    title: 'Upcoming',
                    routeName: 'movies.index',
                    routeParams: {
                        'filter[upcoming]': import.meta.env.SSR
                            ? ''
                            : DateTime.now().toISODate() ?? '',
                    },
                },
                {
                    title: 'Popular',
                    routeName: 'movies.index',
                    routeParams: {
                        sort: '-popularity',
                    },
                },
                {
                    title: 'Series',
                    routeName: 'series.index',
                    routeParams: {},
                },
            ],
        },
        {
            title: 'Models',
            items: [
                {
                    title: 'Recently added',
                    routeName: 'models.index',
                    routeParams: {},
                },
                {
                    title: 'Popular',
                    routeName: 'models.index',
                    routeParams: {
                        sort: '-popularity',
                    },
                },
            ],
        },
        {
            title: 'Studios',
            items: [
                {
                    title: 'Recently added',
                    routeName: 'studios.index',
                    routeParams: {},
                },
            ],
        },
    ];
}

export function getGender(person: Person): string {
    switch (person.id) {
        case 1:
        case 3:
            return 'https://schema.org/Female';
        case 2:
        case 4:
            return 'https://schema.org/Male';
        case 5:
            return 'Non-binary';
    }

    return '';
}
