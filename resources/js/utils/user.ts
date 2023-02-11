import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

export function useAdmin() {
    return computed(() => {
        // @ts-expect-error
        if (page.props?.user?.roles) {
            // @ts-expect-error
            const adminRoles = page.props?.user?.roles?.filter(
                (role) => role.name === 'admin',
            );

            return adminRoles.length > 0;
        }

        return false;
    });
}
