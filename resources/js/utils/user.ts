import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

import { PageProps } from '@/types/inertia';

const page = usePage<PageProps>();

export function useAdmin() {
    return computed(() => {
        if (page.props?.user?.roles) {
            const adminRoles = page.props?.user?.roles?.filter(
                (role) => role.name === 'admin',
            );

            return adminRoles.length > 0;
        }

        return false;
    });
}
