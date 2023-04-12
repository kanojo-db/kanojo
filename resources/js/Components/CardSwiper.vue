<script setup lang="ts">
import 'swiper/css';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { PropType } from 'vue';

import { Movies } from '@/types/models';

import MovieCard from './MovieCard.vue';

const props = defineProps({
    movies: {
        type: Array as PropType<Movies>,
        required: true,
    },
});
</script>

<template>
    <swiper
        :slides-per-group="1"
        slides-per-group-auto
        slides-per-view="auto"
        :space-between="16"
    >
        <swiper-slide
            v-for="movie in props.movies"
            :key="movie.id"
            style="width: 200px"
        >
            <MovieCard :movie="movie" />
        </swiper-slide>
    </swiper>
</template>
