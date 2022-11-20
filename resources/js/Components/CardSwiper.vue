<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import Card from './Card.vue';

import 'swiper/css';

const { movies } = defineProps({
    movies: Object,
});
</script>

<template>
    <swiper
    :slidesPerGroup="1" slidesPerGroupAuto slidesPerView="auto"
    :space-between="16"
  >
    <swiper-slide v-for="movie in movies" :key="movie.id" style="width: 200px">
        <Card :movie="movie" />
    </swiper-slide>
  </swiper>
</template>
