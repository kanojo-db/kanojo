import { acceptHMRUpdate, defineStore } from 'pinia';
import { ref } from 'vue';

const useRouteStore = defineStore('route', () => {
    const current = ref<string>(/* route().current */ '');

    const params = ref({});

    return {
        current,
        params,
    };
});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useRouteStore, import.meta.hot));
}

export default useRouteStore;
