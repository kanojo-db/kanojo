<script setup>
import { Inertia } from '@inertiajs/inertia';
import { computed, defineProps, ref } from 'vue';

import ModelCardSwiper from '@/Components/ModelCardSwiper.vue';
import MovieCard from '@/Components/MovieCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    studio: {
        type: Object,
        required: true,
    },
    movies: {
        type: Array,
        required: true,
    },
    models: {
        type: Array,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
});

const name = computed(() =>
    props.studio.name.en ? props.studio.name.en : props.studio.name.jp,
);

const currentPage = ref(props.movies.current_page);

const goToPage = (page) => {
    const pageLink = props.movies.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        Inertia.get(pageLink.url);
    }
};
</script>

<template>
    <AppLayout :title="name">
        <div class="col bg-grey-3 q-pb-lg">
            <div class="row q-pt-lg q-px-md">
                <div class="col">
                    <div class="row q-mb-sm">
                        <h1 class="text-h4 q-mt-none q-mb-sm ellipsis-2-lines">
                            {{ name }}
                            <q-badge
                                class="text-subtitle1"
                                align="middle"
                                color="grey-7"
                            >
                                {{
                                    $t('web.personShow.moviesCount', {
                                        number: movieCount.toLocaleString(),
                                    })
                                }}
                            </q-badge>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="row q-px-md">
                <div class="row">
                    <h2 class="text-h5 q-mt-none">
                        {{ $t('web.studio.known_for') }}
                    </h2>
                </div>
                <div class="row">
                    <ModelCardSwiper :models="props.models" />
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <div class="row justify-center q-mb-md">
                <q-pagination
                    v-model="currentPage"
                    :max="props.movies.last_page"
                    :max-pages="6"
                    boundary-numbers
                    @update:model-value="goToPage"
                />
            </div>
            <div
                class="fit row wrap justify-start items-start content-start q-gutter-md"
            >
                <MovieCard
                    v-for="movie in props.movies.data"
                    :key="movie.id"
                    :movie="movie"
                />
            </div>
            <div class="row justify-center q-mb-md">
                <q-pagination
                    v-model="currentPage"
                    :max="props.movies.last_page"
                    :max-pages="6"
                    boundary-numbers
                    @update:model-value="goToPage"
                />
            </div>
        </div>
    </AppLayout>
</template>
