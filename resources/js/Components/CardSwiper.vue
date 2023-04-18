<script setup lang="ts">
import { FreeMode, Mousewheel, Scrollbar } from 'swiper';
import 'swiper/css';
import 'swiper/css/free-mode';
import 'swiper/css/mousewheel';
import 'swiper/css/scrollbar';
import { Swiper, SwiperSlide } from 'swiper/vue';
import { PropType } from 'vue';

import { Movies, People } from '@/types/models';
import { isMovie, isPerson } from '@/utils/item';

import ModelCard from './ModelCard.vue';
import MovieCard from './MovieCard.vue';

const props = defineProps({
    items: {
        type: Array as PropType<Movies | People>,
        required: true,
    },
});

const modules = [FreeMode, Mousewheel, Scrollbar];
</script>

<template>
    <swiper
        :free-mode="true"
        :modules="modules"
        :mousewheel="{
            forceToAxis: true,
        }"
        :scrollbar="{ draggable: true }"
        :slides-per-group="1"
        slides-per-group-auto
        slides-per-view="auto"
        :space-between="16"
    >
        <swiper-slide
            v-for="(item, index) in props.items"
            :key="index"
            class="q-mb-md"
            style="width: 200px"
            :virtual-index="index"
        >
            <template v-if="isMovie(item)">
                <MovieCard :movie="item" />
            </template>
            <template v-if="isPerson(item)">
                <ModelCard :model="item" />
            </template>
        </swiper-slide>
    </swiper>
</template>
