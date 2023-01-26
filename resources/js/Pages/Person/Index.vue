<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';

import ModelCard from '@/Components/ModelCard.vue';
import RangeSlider from '@/Components/RangeSlider.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    models: {
        type: Object,
        required: true,
    },
    birthCounts: {
        type: Object,
        required: true,
    },
    heightCounts: {
        type: Object,
        required: true,
    },
    bustCounts: {
        type: Object,
        required: true,
    },
    waistCounts: {
        type: Object,
        required: true,
    },
    hipCounts: {
        type: Object,
        required: true,
    },
});

const route_params = route().params;
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

const currentPage = ref(props.models.current_page);

const dateRange = ref({
    min: filterBornBetween.value[0],
    max: filterBornBetween.value[1],
});

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

const goToPage = (page) => {
    const pageLink = props.models.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        Inertia.get(pageLink.url);
    }
};

function applyFilters() {
    const params = {
        page: props.models.current_page,
    };

    if (dateRange.value.min && dateRange.value.max) {
        params[
            'filter[born]'
        ] = `${dateRange.value.min},${dateRange.value.max}`;
    }

    if (heightRange.value.min && heightRange.value.max) {
        params[
            'filter[height]'
        ] = `${heightRange.value.min},${heightRange.value.max}`;
    }

    if (bustRange.value.min && bustRange.value.max) {
        params[
            'filter[bust]'
        ] = `${bustRange.value.min},${bustRange.value.max}`;
    }

    if (waistRange.value.min && waistRange.value.max) {
        params[
            'filter[waist]'
        ] = `${waistRange.value.min},${waistRange.value.max}`;
    }

    if (hipRange.value.min && hipRange.value.max) {
        params['filter[hip]'] = `${hipRange.value.min},${hipRange.value.max}`;
    }

    Inertia.visit(route('models.index'), params, {
        only: ['models'],
        preserveState: true,
        preserveScroll: true,
    });
}
</script>

<template>
    <AppLayout title="Recently Added Movies">
        <div class="q-pa-md">
            <div class="row q-col-gutter-lg full-width">
                <div class="col-2 q-pl-none">
                    <q-card
                        class="my-card"
                        flat
                        bordered
                    >
                        <q-card-section
                            class="bg-primary text-white row items-center"
                        >
                            <div class="text-weight-bold text-h6">Filter</div>
                        </q-card-section>

                        <q-separator />

                        <div class="q-pa-md">
                            <div class="column">
                                <span class="text-body1 text-weight-bold"
                                    >Year of birth</span
                                >
                                <RangeSlider
                                    v-model="dateRange"
                                    :counts="birthCounts"
                                />
                            </div>
                        </div>

                        <q-separator />

                        <div class="q-pa-md">
                            <div class="col">
                                <span class="text-body1 text-weight-bold"
                                    >Height</span
                                >
                                <RangeSlider
                                    v-model="heightRange"
                                    :counts="heightCounts"
                                    left-label-value="cm"
                                    right-label-value="cm"
                                />
                            </div>
                        </div>

                        <q-separator />

                        <div class="q-pa-md">
                            <div class="col">
                                <span class="text-body1 text-weight-bold"
                                    >Bust Size</span
                                >
                                <RangeSlider
                                    v-model="bustRange"
                                    :counts="bustCounts"
                                    left-label-value="cm"
                                    right-label-value="cm"
                                />
                            </div>
                        </div>

                        <q-separator />

                        <div class="q-pa-md">
                            <div class="col">
                                <span class="text-body1 text-weight-bold"
                                    >Waist Size</span
                                >
                                <RangeSlider
                                    v-model="waistRange"
                                    :counts="waistCounts"
                                    left-label-value="cm"
                                    right-label-value="cm"
                                />
                            </div>
                        </div>

                        <q-separator />

                        <div class="q-pa-md">
                            <div class="col">
                                <span class="text-body1 text-weight-bold"
                                    >Hip Size</span
                                >
                                <RangeSlider
                                    v-model="hipRange"
                                    :counts="hipCounts"
                                    left-label-value="cm"
                                    right-label-value="cm"
                                />
                            </div>
                        </div>

                        <q-separator />

                        <div class="q-pa-md">
                            <q-btn
                                class="full-width"
                                color="primary"
                                label="Apply"
                                @click="applyFilters"
                            />
                        </div>
                    </q-card>
                </div>
                <div class="col col-10">
                    <div class="row justify-center q-mb-md">
                        <q-pagination
                            v-model="currentPage"
                            :max="models.last_page"
                            :max-pages="6"
                            boundary-numbers
                            @update:model-value="goToPage"
                        />
                    </div>
                    <div
                        class="fit row wrap justify-start items-start content-start q-gutter-md"
                    >
                        <ModelCard
                            v-for="model in models.data"
                            :key="model.id"
                            :model="model"
                        />
                    </div>
                    <div class="row justify-center q-mt-md">
                        <q-pagination
                            v-model="currentPage"
                            :max="models.last_page"
                            :max-pages="6"
                            boundary-numbers
                            @update:model-value="goToPage"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
