<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { router } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { computed, ref } from 'vue';

import MdiTimelineText from '~icons/mdi/timeline-text';
import MdiViewGrid from '~icons/mdi/view-grid';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import useRouteStore from '@/stores/route';

import ItemLinks from './ItemLinks.vue';
import MovieTimeline from './MovieTimeline.vue';
import TwoColumnInfo from './TwoColumnInfo.vue';

const props = defineProps({
    item: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const routeStore = useRouteStore();

const currentRoute = computed(() => {
    return routeStore.current;
});

const routeParams = computed(() => {
    return routeStore.params;
});

const currentPage = ref(props.item.movies.current_page);
const loading = ref(false);

const movieView = ref(0);
const itemsPerPage = ref(25);

const updateItemsPerPage = function (value: number) {
    loading.value = true;
    itemsPerPage.value = value;
    currentPage.value = 1;

    router.replace(
        route(currentRoute.value, {
            ...routeParams.value,
            page: currentPage.value,
            per_page: itemsPerPage.value,
        }),
        {
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        },
    );
};
</script>

<template>
    <two-column-info>
        <template #left>
            <h2 class="mb-2 block text-lg font-bold md:hidden">
                {{ $t('model.starredMovied') }}
            </h2>

            <v-toolbar
                border
                rounded
                class="px-2"
            >
                <v-spacer />

                <v-btn-toggle
                    v-model="movieView"
                    divided
                    variant="tonal"
                >
                    <v-btn :icon="MdiViewGrid" />

                    <v-btn :icon="MdiTimelineText" />
                </v-btn-toggle>

                <v-select
                    v-model="itemsPerPage"
                    :items="[25, 50, 100]"
                    hide-details
                    class="ml-2 max-w-fit"
                    @update:model-value="updateItemsPerPage"
                />
            </v-toolbar>

            <paginated-item-grid
                v-if="movieView === 0"
                v-model:loading="loading"
                :items="props.item.movies"
            />

            <movie-timeline
                v-else
                :movies="props.item.movies.data"
            />
        </template>

        <template #right>
            <h2 class="block text-lg font-bold">
                {{ $t('model.alsoKnownAs') }}
            </h2>

            <ul
                v-if="props.item.aliases.length > 0"
                class="mb-2"
            >
                <li
                    v-for="alias in props.item.aliases"
                    :key="alias.id"
                >
                    {{ alias.alias }}
                </li>
            </ul>

            <p
                v-else
                class="mb-2 italic"
            >
                {{ $t('model.noKnownAliases') }}
            </p>

            <h2 class="block text-lg font-bold">
                {{ $t('model.active') }}
            </h2>

            <p
                v-if="props.item.career_start"
                class="mb-2"
            >
                {{
                    $t('model.careerSpan', {
                        start: props.item.career_start
                            ? DateTime.fromISO(
                                  props.item.career_start,
                              ).toLocaleString(DateTime.DATE_MED)
                            : $t('general.present'),
                        end: props.item.career_end
                            ? DateTime.fromISO(
                                  props.item.career_end,
                              ).toLocaleString(DateTime.DATE_MED)
                            : $t('general.present'),
                    })
                }}
            </p>

            <p
                v-else
                class="mb-2"
            >
                {{ $t('general.unknown') }}
            </p>

            <h2 class="block text-lg font-bold">
                {{ $t('general.links') }}
            </h2>

            <item-links :item="props.item" />
        </template>
    </two-column-info>
</template>
