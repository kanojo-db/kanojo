import { computed } from 'vue';

import { User } from '@/types/models';

export function useAdmin(user?: User) {
    return computed(() => {
        if (!user) {
            return false;
        }

        if (user?.roles) {
            const adminRoles = user?.roles?.filter(
                (role) => role.name === 'admin',
            );

            return adminRoles.length > 0;
        }

        return false;
    });
}
