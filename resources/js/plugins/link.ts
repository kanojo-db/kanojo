/* eslint-disable @typescript-eslint/no-explicit-any -- This is only a shim for Vuetify, so we can be fairly loose here. */
import type { App } from 'vue';

import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

export default {
    install(app: App) {
        app.component('RouterLink', {
            useLink(props: any) {
                const href = props.to;
                const currentUrl = computed(() => usePage().url);
                return {
                    route: computed(() => ({ href })),
                    isActive: computed(() => currentUrl.value.startsWith(href)),
                    isExactActive: computed(() => href === currentUrl.value),
                    navigate(e: any) {
                        if (e.shiftKey || e.metaKey || e.ctrlKey) return;
                        e.preventDefault();
                        router.visit(href);
                    },
                };
            },
        });
    },
};
