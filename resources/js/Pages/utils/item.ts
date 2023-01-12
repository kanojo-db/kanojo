import { computed } from 'vue';

/**
 * Returns the title of the movie in the correct language.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} movie - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
export function getTitle(movie) {
    return movie.title.en ? movie.title.en : movie.title.jp;
}

/**
 * Returns the title of the movie in the correct language as a computed value.
 * If the movie has no localized title, the Japanese title is returned.
 *
 * @export
 * @param {*} movie - The movie to get the title from.
 * @returns {string} - The title of the movie.
 */
export function useTitle(movie) {
    return computed(() => {
        return getTitle(movie);
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
export function getName(item) {
    return item.name.en ? item.name.en : item.name.jp;
}

/**
 * Returns the name of the item in the correct language as a computed value.
 * If the item has no localized name, the Japanese name is returned.
 *
 * @export
 * @param {*} item - The item to get the name from.
 * @returns {string} - The name of the item.
 */
export function useName(item) {
    return computed(() => {
        return getName(item);
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
export function useFirstImage(item, collection = 'poster') {
    return computed(() => {
        if (item?.media && item.media.length > 0) {
            return item.media.filter(
                (m) => m.collection_name === collection,
            )?.[0]?.original_url;
        }

        return null;
    });
}
