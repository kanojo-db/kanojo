<script setup lang="ts">
import type { Notification, Paginated } from '@/types/models';
import type { PropType } from 'vue';

import { router } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import ItemCreatePage from '@/Components/ItemCreatePage.vue';
import Pagination from '@/Components/Pagination.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    notifications: {
        type: Object as PropType<Paginated<Notification>>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const { t } = useI18n();

function getNotificationText(notification: Notification) {
    switch (notification.type) {
        case 'App\\Notifications\\ContentReportCreated':
            return t('general.notification.contentReportCreated');
        default:
            return t('general.notification.unknown');
    }
}

const isDeleting = ref<boolean[]>([]);

function markAsRead(notification: Notification, index: number) {
    isDeleting.value[index] = true;

    router.put(
        route('notifications.update', { notification: notification.id }),
        {},
        {
            onFinish: () => {
                isDeleting.value[index] = false;
            },
        },
    );
}

function deleteNotification(notification: Notification, index: number) {
    isDeleting.value[index] = true;

    router.delete(
        route('notifications.destroy', { notification: notification.id }),
        {
            onFinish: () => {
                isDeleting.value[index] = false;
            },
        },
    );
}

watch(
    () => props.notifications.data,
    (notifications) => {
        isDeleting.value = Array.from(
            { length: notifications.length },
            () => false,
        );
    },
    { immediate: true },
);

const currentPage = ref(props.notifications.current_page);
const loading = ref(false);
</script>

<template>
    <item-create-page>
        <template #header>
            <v-container>
                <v-row>
                    <v-col>
                        <h2 class="text-2xl font-bold">
                            {{ $t('general.notifications') }}
                        </h2>
                    </v-col>
                </v-row>
            </v-container>
        </template>

        <template #default>
            <v-container>
                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.notifications"
                            :loading="loading"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-table hover>
                            <thead>
                                <tr>
                                    <th
                                        class="w-max"
                                        scope="col"
                                    >
                                        {{
                                            $t(
                                                'general.notification.description',
                                            )
                                        }}
                                    </th>

                                    <th
                                        class="w-48"
                                        scope="col"
                                    >
                                        {{
                                            $t(
                                                'general.notification.recievedAt',
                                            )
                                        }}
                                    </th>

                                    <th
                                        class="w-48"
                                        scope="col"
                                    />
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-if="loading">
                                    <td
                                        colspan="3"
                                        class="py-16 text-center"
                                    >
                                        <v-progress-circular
                                            indeterminate
                                            color="primary"
                                        />
                                    </td>
                                </tr>

                                <tr
                                    v-if="
                                        !loading &&
                                        props.notifications.data.length === 0
                                    "
                                >
                                    <td colspan="3">
                                        {{
                                            $t(
                                                'general.notification.noNotifications',
                                            )
                                        }}
                                    </td>
                                </tr>

                                <tr
                                    v-for="(notification, index) in props
                                        .notifications.data"
                                    v-else
                                    :key="notification.id"
                                    :class="{
                                        'bg-secondary': !notification.read_at,
                                    }"
                                >
                                    <td class="py-2">
                                        <div
                                            class="flex flex-col align-baseline"
                                        >
                                            <p class="mb-2">
                                                {{
                                                    getNotificationText(
                                                        notification,
                                                    )
                                                }}
                                            </p>

                                            <v-btn
                                                v-if="notification.data.url"
                                                variant="tonal"
                                                :to="notification.data.url"
                                            >
                                                {{
                                                    $t(
                                                        'general.notification.goToItem',
                                                    )
                                                }}
                                            </v-btn>
                                        </div>
                                    </td>

                                    <td>
                                        {{
                                            DateTime.fromISO(
                                                notification.created_at,
                                            ).toLocaleString(
                                                DateTime.DATETIME_MED,
                                            )
                                        }}
                                    </td>

                                    <td>
                                        <v-btn
                                            v-if="!notification.read_at"
                                            variant="tonal"
                                            :text="
                                                $t(
                                                    'general.notification.markAsRead',
                                                )
                                            "
                                            :loading="isDeleting[index]"
                                            @click="
                                                markAsRead(notification, index)
                                            "
                                        />

                                        <v-btn
                                            v-else
                                            color="error"
                                            variant="tonal"
                                            :text="$t('general.delete')"
                                            :loading="isDeleting[index]"
                                            @click="
                                                deleteNotification(
                                                    notification,
                                                    index,
                                                )
                                            "
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.notifications"
                            :loading="loading"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </item-create-page>
</template>
