import { usePage } from '@inertiajs/vue3';
import { acceptHMRUpdate, defineStore } from 'pinia';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const useUserStore = defineStore('user', () => {
    const currentUser = computed(() => usePage().props.user);

    const currentLanguage = computed(() => useI18n().locale);
    const fallbackLanguage = computed(() => useI18n().fallbackLocale);
    const metadataLanguage = ref(useI18n().locale.value);

    return {
        currentUser,
        currentLanguage,
        fallbackLanguage,
        metadataLanguage,
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useUserStore, import.meta.hot));
}

export default useUserStore;
