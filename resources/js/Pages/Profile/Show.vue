<script setup lang="ts">
import type { Audits, Movie, Paginated, User } from '@/types/models';
import type { PropType } from 'vue';

import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useTheme } from 'vuetify';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import ProfileSideMenu from '@/Components/ProfileSideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserHistoryGraph from '@/Components/UserHistoryGraph.vue';
import UserName from '@/Components/UserName.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import useRouteStore from '@/stores/route';
import {
    getItemRouteName,
    getItemRouteParams,
    getName,
    getTitle,
    isMovie,
    isSeries,
} from '@/utils/item';

const props = defineProps({
    userProfile: {
        type: Object as PropType<User>,
        required: true,
    },
    editsCount: {
        type: Number,
        required: true,
    },
    averageRating: {
        type: Number,
        required: true,
    },
    activityThisPastMonth: {
        type: Object as PropType<Record<string, number>>,
        required: true,
    },
    recentActivity: {
        type: Array as PropType<Audits>,
        required: true,
    },
    items: {
        type: Object as PropType<Paginated<Movie>>,
        default: () => ({
            data: [],
            current_page: 1,
            last_page: 1,
        }),
    },
});

defineOptions({
    layout: AppLayout,
});

const locale = useI18n().locale;

const page = usePage();

const theme = useTheme();

const isDark = computed(() => theme.global.current.value.dark);

const routeStore = useRouteStore();

const currentRoute = computed(() => {
    return routeStore.current;
});

const routeParams = computed(() => {
    return routeStore.params;
});

const currentTab = ref(
    // eslint-disable-next-line vue/no-ref-object-destructure -- We just want the value, reactivity is handled by updateCurrentTab.
    routeParams.value.active_tab ?? 'activity',
);

const loading = ref(false);

const updateCurrentTab = (tab: string) => {
    loading.value = true;
    currentTab.value = tab;

    router.get(
        route(currentRoute.value, {
            ...routeParams.value,
            page: 1,
            active_tab: tab,
        }),
        {},
        {
            preserveState: true,
            onFinish: () => {
                loading.value = false;
            },
        },
    );
};
</script>

<template>
    <Head
        :title="`${page?.props?.user?.name} - ${$t('settings.account.title')}`"
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
                            :user="props.userProfile"
                            text-class="text-8xl"
                        />

                        <div class="flex flex-col gap-6">
                            <div class="flex flex-row items-end">
                                <user-name
                                    class="my-0 text-ellipsis text-5xl font-black text-stone-700 dark:text-stone-100"
                                    :user="props.userProfile"
                                />

                                <span
                                    v-if="page?.props?.user"
                                    class="ml-4 text-2xl text-stone-500 dark:text-stone-300"
                                >
                                    {{
                                        $t('profile.memberSince', {
                                            date: DateTime.fromISO(
                                                page?.props?.user?.created_at,
                                            ).toLocaleString(
                                                DateTime.DATE_FULL,
                                            ),
                                        })
                                    }}
                                </span>
                            </div>

                            <div class="flex flex-row items-center gap-6">
                                <div class="flex flex-row items-center gap-4">
                                    <v-progress-circular
                                        :model-value="props.averageRating"
                                        size="72"
                                        :color="
                                            isDark ? 'primary' : 'secondary'
                                        "
                                    >
                                        <span
                                            class="text-xl font-black text-stone-600 dark:text-stone-300"
                                        >
                                            {{
                                                $t('general.x_percent', {
                                                    number: props.averageRating,
                                                })
                                            }}
                                        </span>
                                    </v-progress-circular>

                                    <p
                                        class="w-16 text-stone-500 dark:text-stone-300"
                                    >
                                        {{ $t('profile.averageRating') }}
                                    </p>
                                </div>

                                <v-divider
                                    vertical
                                    class="opacity-40"
                                />

                                <div class="flex flex-row items-center gap-4">
                                    <p
                                        class="text-5xl font-black text-stone-600 dark:text-stone-300"
                                    >
                                        {{ props.editsCount.toLocaleString() }}
                                    </p>

                                    <p
                                        class="w-16 text-stone-500 dark:text-stone-300"
                                    >
                                        {{ $t('profile.edits') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>

    <side-menu-page>
        <template #left>
            <profile-side-menu
                v-model="currentTab"
                @update:model-value="updateCurrentTab"
            />
        </template>

        <template #right>
            <v-window
                v-model="currentTab"
                class="w-full"
            >
                <v-window-item value="activity">
                    <v-row>
                        <v-col>
                            <h2 class="mb-4 text-2xl font-semibold">
                                {{ $t('profile.activity.title') }}
                            </h2>

                            <v-table
                                v-if="props.recentActivity.length > 0"
                                class="rounded-lg"
                                hover
                            >
                                <thead class="bg-stone-200 dark:bg-stone-700">
                                    <tr>
                                        <th scope="col">
                                            {{ $t('profile.activity.type') }}
                                        </th>

                                        <th scope="col">
                                            {{ $t('profile.activity.item') }}
                                        </th>

                                        <th scope="col">
                                            {{ $t('profile.activity.date') }}
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr
                                        v-for="activity in props.recentActivity"
                                        :key="activity.id"
                                    >
                                        <td>
                                            {{
                                                $t(
                                                    `profile.activity.${activity.event}`,
                                                )
                                            }}
                                        </td>

                                        <td>
                                            <Link
                                                v-if="activity.auditable"
                                                :href="
                                                    route(
                                                        `${getItemRouteName(
                                                            activity.auditable,
                                                        )}.show`,
                                                        getItemRouteParams(
                                                            activity.auditable,
                                                        ),
                                                    )
                                                "
                                                class="hover:text-primary"
                                            >
                                                {{
                                                    isMovie(
                                                        activity.auditable,
                                                    ) ||
                                                    isSeries(activity.auditable)
                                                        ? getTitle(
                                                              activity.auditable,
                                                              locale,
                                                          )
                                                        : getName(
                                                              activity.auditable,
                                                              locale,
                                                          )
                                                }}
                                            </Link>

                                            <p v-else>
                                                {{
                                                    $t(
                                                        'profile.activity.itemDeleted',
                                                    )
                                                }}
                                            </p>
                                        </td>

                                        <td>
                                            {{
                                                DateTime.fromISO(
                                                    activity.created_at,
                                                ).toLocaleString(
                                                    DateTime.DATETIME_SHORT,
                                                )
                                            }}
                                        </td>
                                    </tr>
                                </tbody>
                            </v-table>

                            <p v-else>
                                {{ $t('profile.activity.noActivity') }}
                            </p>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <h2 class="mb-4 text-2xl font-semibold">
                                {{ $t('profile.activity.thirtyDayActivity') }}
                            </h2>

                            <user-history-graph
                                :counts="props.activityThisPastMonth"
                            />
                        </v-col>
                    </v-row>
                </v-window-item>

                <v-window-item value="collection">
                    <paginated-item-grid
                        v-model:loading="loading"
                        :items="props.items"
                    >
                        <p>{{ $t('profile.collectionPlaceholder') }}</p>
                    </paginated-item-grid>
                </v-window-item>

                <v-window-item value="favorites">
                    <paginated-item-grid
                        v-model:loading="loading"
                        :items="props.items"
                    >
                        <p>{{ $t('profile.favoritesPlaceholder') }}</p>
                    </paginated-item-grid>
                </v-window-item>

                <v-window-item value="wishlist">
                    <paginated-item-grid
                        v-model:loading="loading"
                        :items="props.items"
                    >
                        <p>{{ $t('profile.wishlistPlaceholder') }}</p>
                    </paginated-item-grid>
                </v-window-item>
            </v-window>
        </template>
    </side-menu-page>
</template>
