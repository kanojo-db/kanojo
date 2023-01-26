import { ref } from 'vue';

const metadataLocale = ref('en-US');

export function useMetadataLocale() {
    return metadataLocale;
}
