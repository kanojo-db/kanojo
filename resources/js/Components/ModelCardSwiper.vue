<script setup>
import 'swiper/css';
import { Swiper, SwiperSlide } from 'swiper/vue';

import ModelCard from './ModelCard.vue';

const props = defineProps({
    models: {
        type: Array,
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
            v-for="model in props.models"
            :key="model.id"
            style="width: 200px"
        >
            <ModelCard :model="model" />
        </swiper-slide>
    </swiper>
</template>
