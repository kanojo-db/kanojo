<script setup>
import { ref } from "vue";
import { Inertia } from '@inertiajs/inertia'

import AppLayout from "@/Layouts/AppLayout.vue";
import Card from '@/Components/Card.vue';

const { movies } = defineProps({
    movies: Object,
});

const currentPage = ref(movies.current_page);

const goToPage = (page) => {
    const pageLink = movies.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        Inertia.get(pageLink.url);
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
            <div class="fit row wrap justify-start items-start content-start q-gutter-md">
                <Card v-for="movie in movies.data" :key="movie.id" :movie="movie" />
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
