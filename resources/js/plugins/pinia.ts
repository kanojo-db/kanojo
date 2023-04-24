import { router } from '@inertiajs/vue3';
import { createPinia } from 'pinia';

import useRouteStore from '@/stores/route';

const pinia = createPinia();

// We can't use router events in SSR.
if (import.meta.env.SSR === false) {
    // Setup automatic route change detection using Inertia.js events, since Ziggy is not reactive.
    router.on('navigate', () => {
        useRouteStore().current = route().current();
        useRouteStore().params = route().params;
    });
}

export default pinia;
