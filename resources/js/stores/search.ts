import { acceptHMRUpdate, defineStore } from 'pinia';

const useSearchStore = defineStore('search', () => {});

if (import.meta.hot) {
    import.meta.hot.accept(acceptHMRUpdate(useSearchStore, import.meta.hot));
}

export default useSearchStore;
