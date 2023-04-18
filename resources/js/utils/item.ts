import { computed } from 'vue';

import type { Movie, Person, Studio } from '@/types/models';

export function cleanLocale(locale: string) {
    return locale.replace('!', '');
}

/**
 * Returns the title of the movie in the correct language.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} movie - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
function getTitle(movie: Movie, locale: string): string {
    return movie.title[locale] ? movie.title[locale] : movie.title['ja-JP'];
}

/**
 * Returns the title of the movie in the correct language as a computed value.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} movie - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
export function useTitle(movie: Movie, locale: string) {
    // In case this has fallback suppression, clean the locale
    locale = cleanLocale(locale);

    return computed(() => {
        return getTitle(movie, locale);
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
export function getName(item: Person | Studio, locale: string): string {
    return item.name[locale] ? item.name[locale] : item.name['ja-JP'];
}

/**
 * Returns the name of the item in the correct language as a computed value.
 * If the item has no localized name, the Japanese name is returned.
 *
 * @export
 * @param {*} item - The item to get the name from.
 * @returns {string} - The name of the item.
 */
export function useName(item: Person | Studio, locale: string) {
    // In case this has fallback suppression, clean the locale
    locale = cleanLocale(locale);

    return computed(() => {
        return getName(item, locale);
    });
}

/**
 * Returns the URL of first image of the item as a computed value.
 *
 * @export
 * @param {*} item - The item to get the image from.
 * @param {string} [collection='poster'] - The media collection to get the image from.
 * @returns {string} - The URL to the first image of the item for the given collection.
 */
export function useFirstImage(
    item: Movie | Person,
    collection = 'front_cover',
) {
    return computed(() => {
        if (item?.media && item.media.length > 0) {
            const images = item.media.filter(
                (m: any) => m.collection_name === collection,
            );

            if (images.length > 0) {
                return images[0].original_url;
            }
        }

        return null;
    });
}

/**
 * Determines whether the provided item is a Movie object.
 *
 * @param {Movie | Person | Studio} item - The item to check.
 * @returns {boolean} - True if the item is a Movie object, false otherwise.
 */
export function isMovie(item: Movie | Person | Studio): item is Movie {
    return (item as Movie).title !== undefined;
}

/**
 * Determines whether the provided item is a Person object.
 *
 * @param {Movie | Person | Studio} item - The item to check.
 * @returns {boolean} - True if the item is a Person object, false otherwise.
 */
export function isPerson(item: Movie | Person | Studio): item is Person {
    return (item as Person).name !== undefined;
}
