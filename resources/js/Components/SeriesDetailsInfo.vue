<script setup lang="ts">
import type { Series } from '@/types/models';
import type { PropType } from 'vue';

import { Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiTimelineText from '~icons/mdi/timeline-text';
import MdiViewGrid from '~icons/mdi/view-grid';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import useRouteStore from '@/stores/route';
import { useName } from '@/utils/item';

import MovieTimeline from './MovieTimeline.vue';
import TwoColumnInfo from './TwoColumnInfo.vue';

const props = defineProps({
    item: {
        type: Object as PropType<Series>,
        required: true,
    },
});

const locale = useI18n().locale;

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

const studioName = useName(props.item.studio, locale.value);
</script>

<template>
    <two-column-info>
        <template #left>
            <h2 class="mb-2 block text-lg font-bold md:hidden">
                {{ $t('series.moviesPartOf') }}
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
                v-else-if="movieView === 1"
                :movies="props.item.movies.data"
            />
        </template>

        <template #right>
            <div
                v-if="props.item.studio"
                itemprop="productionCompany"
                itemscope
                itemtype="https://schema.org/Corporation"
            >
                <span class="block text-lg font-bold">
                    {{ $t('movie.show.studio') }}
                </span>

                <Link
                    :href="route('studios.show', item.studio)"
                    itemprop="name"
                >
                    {{ studioName }}
                </Link>
            </div>
        </template>
    </two-column-info>
</template>
