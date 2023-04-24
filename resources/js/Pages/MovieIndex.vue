<script setup lang="ts">
import type { Counts } from '@/types/internal';
import type { Movie, MovieTypes, Paginated } from '@/types/models';
import type { PropType } from 'vue';

import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import RangeSlider from '@/Components/RangeSlider.vue';
import SideMenu from '@/Components/SideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

interface MovieIndexParams {
    page?: number;
    filter?: {
        age?: string;
        type?: string;
    };
}

const props = defineProps({
    movies: {
        type: Object as PropType<Paginated<Movie>>,
        required: true,
    },
    ageCounts: {
        type: Array as PropType<Counts>,
        required: true,
    },
    movieTypes: {
        type: Array as PropType<MovieTypes>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const routeParams = route().params as MovieIndexParams;

const localFilterAgeBetween = ref<(number | null)[]>([null, null]);

const filterAgeBetween = computed<(number | null)[]>({
    get() {
        return localFilterAgeBetween.value
            ? localFilterAgeBetween.value
            : routeParams.filter?.age?.split(',').map(Number);
    },
    set(value: (number | null)[]) {
        localFilterAgeBetween.value = value;
    },
});

const sortBy = ref(route().params?.sort || '-release_date');

const filterType = ref(parseInt(routeParams.filter?.type ?? '0', 10));

const ageRange = ref<[number | null, number | null]>([
    // eslint-disable-next-line vue/no-ref-object-destructure -- Expected, update is handled by range-slider
    filterAgeBetween.value[0],
    // eslint-disable-next-line vue/no-ref-object-destructure -- Expected, update is handled by range-slider
    filterAgeBetween.value[1],
]);

const loading = ref(false);

function applyFilters() {
    loading.value = true;

    let changePage = false;

    const params: MovieIndexParams = {
        ...routeParams,
    };

    if (sortBy.value) {
        params.sort = sortBy.value;
    }

    if (
        filterType.value ||
        ageRange.value[0] !== null ||
        ageRange.value[1] !== null
    ) {
        params.filter = {};

        if (filterType.value) {
            params.filter.type = filterType.value.toString();
            changePage = true;
        }

        if (ageRange.value[0] !== null && ageRange.value[1] !== null) {
            params.filter.age = `${ageRange.value[0]},${ageRange.value[1]}`;
            changePage = true;
        }
    }

    if (changePage) {
        params.page = 1;
    }

    router.get(route('movies.index'), params, {
        only: ['movies'],
        preserveState: true,
        preserveScroll: true,
        onFinish: () => {
            loading.value = false;
        },
    });
}
</script>

<template>
    <Head title="Movies" />

    <side-menu-page>
        <template #left>
            <side-menu title="Filter">
                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Sort by
                    </v-list-item-title>

                    <v-select
                        v-model="sortBy"
                        :items="[
                            { value: 'release_date', name: 'Release date' },
                            {
                                value: '-release_date',
                                name: 'Release date (desc)',
                            },
                            { value: 'title', name: 'Title' },
                            { value: '-title', name: 'Title (desc)' },
                            { value: 'release_date', name: 'Release date' },
                            {
                                value: '-release_date',
                                name: 'Release date (desc)',
                            },
                            { value: 'popularity', name: 'Popularity' },
                            { value: '-popularity', name: 'Popularity (desc)' },
                            { value: 'updated_at', name: 'Updated at' },
                            { value: '-updated_at', name: 'Updated at (desc)' },
                        ]"
                        item-value="value"
                        item-title="name"
                        hide-details
                        :disabled="loading"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Type
                    </v-list-item-title>

                    <v-select
                        v-model="filterType"
                        :items="[{ id: 0, name: 'All' }, ...props.movieTypes]"
                        item-value="id"
                        item-title="name"
                        hide-details
                        :disabled="loading"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Age of featured models
                    </v-list-item-title>

                    <range-slider
                        v-model="ageRange"
                        :counts="ageCounts"
                        left-label-value=" years old"
                        right-label-value=" years old"
                        :disabled="loading"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>
            </side-menu>
        </template>

        <template #right>
            <paginated-item-grid
                v-model:loading="loading"
                :items="props.movies"
            />
        </template>
    </side-menu-page>
</template>
