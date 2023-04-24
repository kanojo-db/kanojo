import { ref } from 'vue';

const metadataLocale = ref('en-US');

export function addFont(fontUrl: string) {
    // If we're in SSR, don't add the font.
    if (import.meta.env.SSR) {
        return;
    }

    const fontLoader = document.createElement('link');

    fontLoader.setAttribute('href', fontUrl);

    fontLoader.setAttribute('rel', 'stylesheet');

    document.head.appendChild(fontLoader);
}

/**
 * Removes the fallback suppression from the locale.
 * This is used to determine the correct locale to use when getting localized data.
 **/
export function cleanLocale(locale: string) {
    return locale.replace('!', '');
}

export function useMetadataLocale() {
    return metadataLocale;
}
