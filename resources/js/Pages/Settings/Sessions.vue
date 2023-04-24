<script setup lang="ts">
import type { Sessions, User } from '@/types/models';
import type { PropType } from 'vue';

import { Head, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';

import SideMenuPage from '@/Components/SideMenuPage.vue';
import SideMenuUser from '@/Components/SideMenuUser.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserName from '@/Components/UserName.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    sessions: {
        type: Array as PropType<Sessions>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const page = usePage();
</script>

<template>
    <Head
        :title="`${page?.props?.user?.name} - ${$t('settings.sessions.title')}`"
    />

    <div class="flex flex-col bg-stone-300 dark:bg-stone-700">
        <v-container>
            <v-row align="center">
                <v-col>
                    <div
                        class="my-8 flex flex-col gap-6 lg:flex-row lg:items-center"
                    >
                        <user-avatar
                            :size="160"
                            :user="page.props.user as User"
                            text-class="text-8xl"
                        />

                        <div class="flex flex-col gap-6">
                            <div class="flex flex-row items-end">
                                <user-name
                                    class="my-0 text-ellipsis text-5xl font-black text-stone-700 dark:text-stone-100"
                                    :user="page.props.user as User"
                                />
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>

    <side-menu-page>
        <template #left>
            <side-menu-user />
        </template>

        <template #right>
            <v-table hover>
                <thead>
                    <tr>
                        <th scope="col">
                            {{ $t('settings.sessions.ip_address') }}
                        </th>

                        <th scope="col">
                            {{ $t('settings.sessions.browser') }}
                        </th>

                        <th scope="col">
                            {{ $t('settings.sessions.platform') }}
                        </th>

                        <th scope="col">
                            {{ $t('settings.sessions.last_active') }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="session in props.sessions"
                        :key="session.id"
                    >
                        <td>
                            <v-chip
                                v-if="session.is_current_device"
                                color="primary"
                            >
                                {{ $t('settings.sessions.current') }}
                            </v-chip>

                            {{ session.ip_address }}
                        </td>

                        <td>
                            {{ session.browser }}
                        </td>

                        <td>
                            {{ session.platform }}
                        </td>

                        <td>
                            {{
                                session.last_active
                                    ? DateTime.fromISO(
                                          session.last_active,
                                      ).toRelative()
                                    : $t('settings.sessions.unknown')
                            }}
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </template>
    </side-menu-page>
</template>
