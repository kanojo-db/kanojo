<script setup>
import { Swiper, SwiperSlide } from 'swiper/vue';
import ModelCard from './ModelCard.vue';

import 'swiper/css';

const { movies: models } = defineProps({
    models: Object,
});
</script>

<template>
    <swiper
    :slidesPerGroup="1" slidesPerGroupAuto slidesPerView="auto"
    :space-between="16"
  >
    <swiper-slide v-for="model in models" :key="model.id" style="width: 200px">
        <ModelCard :model="model" />
    </swiper-slide>
  </swiper>
</template>
