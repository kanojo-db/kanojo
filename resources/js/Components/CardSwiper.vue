<script setup lang="ts">
import type { Movies, People, Series, Studios } from '@/types/models';
import type { PropType } from 'vue';

import { FreeMode, Mousewheel, Scrollbar } from 'swiper/modules';
import { Swiper, SwiperSlide } from 'swiper/vue';

import ModelCard from '@/Components/ModelCard.vue';
import MovieCard from '@/Components/MovieCard.vue';
import SeriesCard from '@/Components/SeriesCard.vue';
import StudioCard from '@/Components/StudioCard.vue';
import { isMovie, isPerson, isSeries, isStudio } from '@/utils/item';

const props = defineProps({
    items: {
        type: Array as PropType<
            Movies | (People & { movies_count: number }[]) | Series[] | Studios
        >,
        required: true,
    },
});

const modules = [FreeMode, Mousewheel, Scrollbar];
</script>

<template>
    <swiper
        free-mode
        :modules="modules"
        :mousewheel="{
            forceToAxis: true,
        }"
        :scrollbar="{ draggable: true }"
        :slides-per-group="1"
        slides-per-group-auto
        slides-per-view="auto"
    >
        <swiper-slide
            v-for="(item, index) in props.items"
            :key="index"
            class="mb-2 mr-4 max-w-[100px] md:max-w-[200px]"
            :virtual-index="index"
        >
            <template v-if="isMovie(item)">
                <movie-card :movie="item" />
            </template>

            <template v-if="isPerson(item)">
                <model-card :model="item" />
            </template>

            <template v-if="isSeries(item)">
                <series-card :series="item" />
            </template>

            <template v-if="isStudio(item)">
                <studio-card :studio="item" />
            </template>
        </swiper-slide>
    </swiper>
</template>
