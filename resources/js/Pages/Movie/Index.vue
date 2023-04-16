<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';

import MovieCard from '@/Components/MovieCard.vue';
import RangeSlider from '@/Components/RangeSlider.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Counts } from '@/types/internal';
import { Movie, Paginated } from '@/types/models';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    movies: {
        type: Object as PropType<Paginated<Movie>>,
        required: true,
    },
    ageCounts: {
        type: Array as PropType<Counts>,
        required: true,
    },
});

const route_params = $route().params;
const filterAgeBetween = ref(
    route_params?.filter?.age?.split(',') || [null, null],
);

const ageRange = ref({
    min: filterAgeBetween.value[0],
    max: filterAgeBetween.value[1],
});

const currentPage = ref(props.movies.current_page);

const goToPage = (page: number) => {
    const pageLink = props.movies.links.find(
        (link) => link.label === page.toString(),
    );

    if (pageLink && pageLink.url) {
        router.get(pageLink.url, undefined, {
            only: ['movies'],
            preserveState: true,
            preserveScroll: true,
        });
    }
};

function applyFilters() {
    const params = {
        page: props.movies.current_page,
    };

    if (ageRange.value.min && ageRange.value.max) {
        params['filter[age]'] = `${ageRange.value.min},${ageRange.value.max}`;
    }

    router.get(
        $route('movies.index'),
        { data: params },
        {
            only: ['movies'],
            preserveState: true,
            preserveScroll: true,
        },
    );
}
</script>

<template>
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
                        <div class="col">
                            <span class="text-body1 text-weight-bold">
                                Age of featured models
                            </span>
                            <RangeSlider
                                v-model="ageRange"
                                :counts="ageCounts"
                                left-label-value=" years old"
                                right-label-value=" years old"
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
                        :max="movies.last_page"
                        :max-pages="6"
                        boundary-numbers
                        @update:model-value="goToPage"
                    />
                </div>
                <div
                    class="row wrap justify-start items-start content-start q-gutter-md"
                >
                    <MovieCard
                        v-for="movie in movies.data"
                        :key="movie.id"
                        :movie="movie"
                    />
                </div>
                <div class="row justify-center q-mt-md">
                    <q-pagination
                        v-model="currentPage"
                        :max="movies.last_page"
                        :max-pages="6"
                        boundary-numbers
                        @update:model-value="goToPage"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
