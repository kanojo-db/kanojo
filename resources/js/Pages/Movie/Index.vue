<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

import MovieCard from '@/Components/MovieCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    movies: {
        type: Object,
        required: true,
    },
});

const currentPage = ref(props.movies.current_page);

const goToPage = (page) => {
    const pageLink = props.movies.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        router.get(pageLink.url);
    }
};
</script>

<template>
    <AppLayout title="Recently Added Movies">
        <div class="q-pa-md">
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
                class="fit row wrap justify-start items-start content-start q-gutter-md"
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
    </AppLayout>
</template>
