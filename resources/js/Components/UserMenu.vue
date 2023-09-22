<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { useDark, useToggle } from '@vueuse/core';
import { useTheme } from 'vuetify';

import MdiAccount from '~icons/mdi/account';

import UserAvatar from '@/Components/UserAvatar.vue';
import useUserStore from '@/stores/user';

const page = usePage();

const isAdmin = page?.props?.user?.is_administrator;

const userStore = useUserStore();

const theme = useTheme();

const isDark = useDark({
    onChanged(isDark, defaultHandler) {
        theme.global.name.value = isDark ? 'dark' : 'light';

        defaultHandler(isDark ? 'dark' : 'light');
    },
});
const toggleDark = useToggle(isDark);

const logout = () => {
    page.props.user = null;
    router.post(
        route('logout'),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.reload();
            },
        },
    );
};
</script>

<template>
    <v-menu
        v-if="userStore.currentUser"
        open-on-hover
        offset="8px"
    >
        <template #activator="{ props }">
            <v-badge
                v-if="
                    userStore.currentUser.unread_notifications_count !== null &&
                    userStore.currentUser.unread_notifications_count > 0
                "
                color="secondary"
                dot
            >
                <v-btn
                    v-bind="props"
                    variant="plain"
                    rounded
                    icon
                    class="overflow-hidden px-0"
                >
                    <user-avatar
                        label="Open user menu"
                        :user="userStore.currentUser"
                        size="large"
                        :placeholder-size="'md'"
                    />
                </v-btn>
            </v-badge>

            <v-btn
                v-else
                v-bind="props"
                variant="plain"
                rounded
                icon
                class="overflow-hidden px-0"
            >
                <user-avatar
                    label="Open user menu"
                    :user="userStore.currentUser"
                    size="large"
                    :placeholder-size="'md'"
                />
            </v-btn>
        </template>

        <v-list width="200">
            <v-list-item
                height="72"
                :to="
                    route('profile.show', {
                        user: userStore.currentUser?.id ?? 0,
                    })
                "
            >
                <v-list-item-title>
                    <span class="font-bold">
                        {{ userStore.currentUser.name }}
                    </span>
                </v-list-item-title>

                <v-list-item-subtitle>
                    {{ $t('general.view_profile') }}
                </v-list-item-subtitle>
            </v-list-item>

            <v-divider />

            <v-list-item :to="route('notifications.index')">
                {{ $t('general.notifications') }}

                <template #append>
                    <v-badge
                        v-if="
                            userStore.currentUser.unread_notifications_count !==
                                null &&
                            userStore.currentUser.unread_notifications_count > 0
                        "
                        color="secondary"
                        :content="
                            userStore.currentUser.unread_notifications_count
                        "
                    />
                </template>
            </v-list-item>

            <v-divider />

            <v-list-item link>
                <v-switch
                    :model-value="isDark"
                    :label="$t('general.darkMode')"
                    hide-details
                    @update:model-value="toggleDark"
                />
            </v-list-item>

            <v-divider />

            <v-list-item :to="route('settings.account')">
                {{ $t('general.pages.settings') }}
            </v-list-item>

            <v-divider />

            <template v-if="isAdmin">
                <v-list-item href="/telescope">
                    {{ $t('admin.telescope') }}
                </v-list-item>

                <v-list-item href="/horizon">
                    {{ $t('admin.horizon') }}
                </v-list-item>

                <v-divider />
            </template>

            <v-list-item
                link
                @click="logout"
            >
                {{ $t('general.logout') }}
            </v-list-item>
        </v-list>
    </v-menu>

    <template v-else>
        <v-btn
            class="hidden lg:flex"
            variant="plain"
            :to="route('login')"
        >
            {{ $t('general.login') }}
        </v-btn>

        <v-btn
            class="hidden lg:flex"
            variant="plain"
            :to="route('register')"
        >
            {{ $t('general.register') }}
        </v-btn>

        <v-menu
            open-on-hover
            offset="8px"
        >
            <template #activator="{ props }">
                <v-btn
                    aria-label="Open user menu"
                    class="lg:hidden"
                    v-bind="props"
                    :icon="MdiAccount"
                />
            </template>

            <v-list>
                <v-list-item :to="route('login')">
                    {{ $t('general.login') }}
                </v-list-item>

                <v-list-item :to="route('register')">
                    {{ $t('general.register') }}
                </v-list-item>
            </v-list>
        </v-menu>
    </template>
</template>
