import type {
    Country,
    Gender,
    Item,
    Media,
    Movie,
    Person,
    Series,
    Studio,
    Tag,
} from '@/types/models';

import { computed } from 'vue';

import { cleanLocale } from '@/utils/lang';

/**
 * Returns the title of the movie in the correct language.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} movie - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
export function getTitle(
    item: Movie | Series,
    locale: string,
    fallback = true,
): string | undefined {
    return item.title[cleanLocale(locale)]
        ? item.title[cleanLocale(locale)]
        : fallback
        ? item.title['ja-JP']
        : undefined;
}

/**
 * Returns the title of the movie in the correct language as a computed value.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} item - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
export function useTitle(item: Movie | Series, locale: string) {
    return computed(() => {
        return getTitle(item, cleanLocale(locale));
    });
}

/**
 * Returns the name of the item in the correct language.
 * If the item has no localized name, the Japanese name is returned.
 *
 * @export
 * @param {*} item - The item to get the name from.
 * @returns {string} - The name of the item.
 */
export function getName(
    item: Person | Studio | Tag | Gender,
    locale: string,
    fallback = true,
): string | undefined {
    if (typeof item.name === 'string') {
        return item.name;
    }

    return item.name[cleanLocale(locale)]
        ? item.name[cleanLocale(locale)]
        : fallback
        ? item.name['ja-JP']
        : undefined;
}

/**
 * Returns the name of the item in the correct language as a computed value.
 * If the item has no localized name, the Japanese name is returned.
 *
 * @export
 * @param {*} item - The item to get the name from.
 * @returns {string} - The name of the item.
 */
export function useName(item: Person | Studio | Tag, locale: string) {
    return computed(() => {
        return getName(item, cleanLocale(locale));
    });
}

/**
 * Determines whether the provided item is a Media object.
 *
 * @param {Item | Media} item - The item to check.
 * @returns {boolean} - True if the item is a Media object, false otherwise.
 */
export function isMedia(item: Item | Media): item is Media {
    return (item as Media).collection_name !== undefined;
}

/**
 * Determines whether the provided item is a Movie object.
 *
 * @param {Item} item - The item to check.
 * @returns {boolean} - True if the item is a Movie object, false otherwise.
 */
export function isMovie(item: Item): item is Movie {
    return (
        (item as Movie).title !== undefined &&
        (item as Movie).length !== undefined
    );
}

/**
 * Determines whether the provided item is a Person object.
 *
 * @param {Item} item - The item to check.
 * @returns {boolean} - True if the item is a Person object, false otherwise.
 */
export function isPerson(item: Item): item is Person {
    return (item as Person).birthdate !== undefined;
}

/**
 * Determines whether the provided item is a Studio object.
 *
 * @param {Item} item - The item to check.
 * @returns {boolean} - True if the item is a Studio object, false otherwise.
 */
export function isStudio(item: Item): item is Studio {
    return (item as Studio).founded_date !== undefined;
}

/**
 * Determines whether the provided item is a Series object.
 *
 * @param {Item} item - The item to check.
 * @returns {boolean} - True if the item is a Series object, false otherwise.
 */
export function isSeries(item: Item): item is Series {
    return (
        (item as Series).title !== undefined &&
        (item as Movie).length === undefined
    );
}

export function getItemRouteName(item: Item): string | undefined {
    if (isMovie(item)) {
        return 'movies';
    }

    if (isPerson(item)) {
        return 'models';
    }

    if (isStudio(item)) {
        return 'studios';
    }

    if (isSeries(item)) {
        return 'series';
    }

    return undefined;
}

export function useItemRouteName(item: Item) {
    return computed(() => {
        return getItemRouteName(item);
    });
}

export function getItemRouteParams(item: Item): Record<string, string> {
    if (isMovie(item)) {
        return { movie: item.slug };
    }

    if (isPerson(item)) {
        return { model: item.slug };
    }

    if (isStudio(item)) {
        return { studio: item.slug };
    }

    if (isSeries(item)) {
        return { series: item.slug };
    }

    return {};
}

export function useItemRouteParams(item: Item) {
    return computed(() => {
        return getItemRouteParams(item);
    });
}

export async function getCountryFlag(country: Country | null) {
    switch (country?.name) {
        case 'Japan':
            return (await import('~icons/flagpack/jp')).default;
        case 'United States':
            return (await import('~icons/flagpack/us')).default;
        case 'United Kingdom':
            return (await import('~icons/flagpack/gb-ukm')).default;
        default:
            return (await import('~icons/mdi/help')).default;
    }
}
