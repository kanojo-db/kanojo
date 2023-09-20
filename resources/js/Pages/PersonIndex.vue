<script setup lang="ts">
import type { Count } from '@/types/internal';
import type { Paginated, Person } from '@/types/models';
import type { PropType } from 'vue';

import { Head, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

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

// eslint-disable-next-line vue/no-ref-object-destructure
const dateRange = computed(() => [
    filterBornBetween.value[0],
    filterBornBetween.value[1],
]);

const heightRange = computed(() => {
    return {
        min: filterHeightBetween.value[0],
        max: filterHeightBetween.value[1],
    };
});

const bustRange = computed(() => {
    return {
        min: filterBustBetween.value[0],
        max: filterBustBetween.value[1],
    };
});

const waistRange = computed(() => {
    return {
        min: filterWaistBetween.value[0],
        max: filterWaistBetween.value[1],
    };
});

const hipRange = computed(() => {
    return {
        min: filterHipBetween.value[0],
        max: filterHipBetween.value[1],
    };
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
                        {{ $t('models.sortBy') }}
                    </v-list-item-title>

                    <v-select
                        v-model="sortBy"
                        :items="[
                            { value: 'movies', name: $t('models.sort.movies') },
                            {
                                value: '-movies',
                                name: $t('models.sort.moviesDesc'),
                            },
                            {
                                value: 'birthdate',
                                name: $t('models.sort.birthdate'),
                            },
                            {
                                value: '-birthdate',
                                name: $t('models.sort.birthdateDesc'),
                            },
                            { value: 'height', name: $t('models.sort.height') },
                            {
                                value: '-height',
                                name: $t('models.sort.heightDesc'),
                            },
                            { value: 'bust', name: $t('models.sort.bust') },
                            {
                                value: '-bust',
                                name: $t('models.sort.bustDesc'),
                            },
                            { value: 'waist', name: $t('models.sort.waist') },
                            {
                                value: '-waist',
                                name: $t('models.sort.waistDesc'),
                            },
                            { value: 'hip', name: $t('models.sort.hip') },
                            { value: '-hip', name: $t('models.sort.hipDesc') },
                            {
                                value: 'popularity',
                                name: $t('models.sort.popularity'),
                            },
                            {
                                value: '-popularity',
                                name: $t('models.sort.popularityDesc'),
                            },
                            {
                                value: 'created_at',
                                name: $t('models.sort.createdAt'),
                            },
                            {
                                value: '-created_at',
                                name: $t('models.sort.createdAtDesc'),
                            },
                            {
                                value: 'updated_at',
                                name: $t('models.sort.updatedAt'),
                            },
                            {
                                value: '-updated_at',
                                name: $t('models.sort.updatedAtDesc'),
                            },
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
                        {{ $t('models.yearOfBirth') }}
                    </v-list-item-title>

                    <range-slider
                        v-model="dateRange"
                        :counts="birthCounts"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>

                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        {{ $t('models.height') }}
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
                        {{ $t('models.bustSize') }}
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
                        {{ $t('models.waistSize') }}
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
                        {{ $t('models.hipSize') }}
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
