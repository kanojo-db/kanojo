<script setup lang="ts">
import type { Count } from '@/types/internal';
import type { Paginated, Person } from '@/types/models';
import type { PropType } from 'vue';

import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import RangeSlider from '@/Components/RangeSlider.vue';
import SideMenu from '@/Components/SideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    models: {
        type: Object as PropType<Paginated<Person>>,
        required: true,
    },
    birthCounts: {
        type: Object as PropType<Count>,
        required: true,
    },
    heightCounts: {
        type: Object as PropType<Count>,
        required: true,
    },
    bustCounts: {
        type: Object as PropType<Count>,
        required: true,
    },
    waistCounts: {
        type: Object as PropType<Count>,
        required: true,
    },
    hipCounts: {
        type: Object as PropType<Count>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const route_params = route().params;

const sortBy = ref(route_params?.sort || '-created_at');

const filterBornBetween = ref(
    route_params?.filter?.born?.split(',') || [null, null],
);
const filterHeightBetween = ref(
    route_params?.filter?.height?.split(',') || [null, null],
);
const filterBustBetween = ref(
    route_params?.filter?.bust?.split(',') || [null, null],
);
const filterWaistBetween = ref(
    route_params?.filter?.waist?.split(',') || [null, null],
);
const filterHipBetween = ref(
    route_params?.filter?.hip?.split(',') || [null, null],
);

const dateRange = ref([filterBornBetween.value[0], filterBornBetween.value[1]]);

const heightRange = ref({
    min: filterHeightBetween.value[0],
    max: filterHeightBetween.value[1],
});

const bustRange = ref({
    min: filterBustBetween.value[0],
    max: filterBustBetween.value[1],
});

const waistRange = ref({
    min: filterWaistBetween.value[0],
    max: filterWaistBetween.value[1],
});

const hipRange = ref({
    min: filterHipBetween.value[0],
    max: filterHipBetween.value[1],
});

const loading = ref(false);

function applyFilters() {
    const params = {
        page: props.models.current_page,
        filter: {
            born: undefined as string | undefined,
            height: undefined as string | undefined,
            bust: undefined as string | undefined,
            waist: undefined as string | undefined,
            hip: undefined as string | undefined,
        },
        sort: undefined as string | undefined,
    };

    if (dateRange.value.min && dateRange.value.max) {
        params.filter.born = `${dateRange.value.min},${dateRange.value.max}`;
    }

    if (heightRange.value.min && heightRange.value.max) {
        params.filter.height = `${heightRange.value.min},${heightRange.value.max}`;
    }

    if (bustRange.value.min && bustRange.value.max) {
        params.filter.bust = `${bustRange.value.min},${bustRange.value.max}`;
    }

    if (waistRange.value.min && waistRange.value.max) {
        params.filter.waist = `${waistRange.value.min},${waistRange.value.max}`;
    }

    if (hipRange.value.min && hipRange.value.max) {
        params.filter.hip = `${hipRange.value.min},${hipRange.value.max}`;
    }

    if (sortBy.value) {
        params.sort = sortBy.value;
    }

    router.get(route('models.index'), params, {
        only: ['models'],
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Models" />

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
                            { value: 'movies', name: 'Number of movies' },
                            {
                                value: '-movies',
                                name: 'Number of movies (desc)',
                            },
                            { value: 'birthdate', name: 'Birthdate' },
                            { value: '-birthdate', name: 'Birthdate (desc)' },
                            { value: 'height', name: 'Height' },
                            { value: '-height', name: 'Height (desc)' },
                            { value: 'bust', name: 'Bust' },
                            { value: '-bust', name: 'Bust (desc)' },
                            { value: 'waist', name: 'Waist' },
                            { value: '-waist', name: 'Waist (desc)' },
                            { value: 'hip', name: 'Hip' },
                            { value: '-hip', name: 'Hip (desc)' },
                            { value: 'popularity', name: 'Popularity' },
                            { value: '-popularity', name: 'Popularity (desc)' },
                            { value: 'created_at', name: 'Created at' },
                            { value: '-created_at', name: 'Created at (desc)' },
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
                        Year of birth
                    </v-list-item-title>

                    <range-slider
                        v-model="dateRange"
                        :counts="birthCounts"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Height
                    </v-list-item-title>

                    <range-slider
                        v-model="heightRange"
                        :counts="heightCounts"
                        left-label-value="cm"
                        right-label-value="cm"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Bust Size
                    </v-list-item-title>

                    <range-slider
                        v-model="bustRange"
                        :counts="bustCounts"
                        left-label-value="cm"
                        right-label-value="cm"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Waist Size
                    </v-list-item-title>

                    <range-slider
                        v-model="waistRange"
                        :counts="waistCounts"
                        left-label-value="cm"
                        right-label-value="cm"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        Hip Size
                    </v-list-item-title>

                    <range-slider
                        v-model="hipRange"
                        :counts="hipCounts"
                        left-label-value="cm"
                        right-label-value="cm"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>
            </side-menu>
        </template>

        <template #right>
            <paginated-item-grid
                v-model:loading="loading"
                :items="props.models"
            />
        </template>
    </side-menu-page>
</template>
