<script setup lang="ts">
import type { Movies, People, Users } from '@/types/models';
import type { PropType } from 'vue';

import CardSwiper from '@/Components/CardSwiper.vue';
import Leaderboard from '@/Components/Leaderboard.vue';
import WelcomeHero from '@/Components/WelcomeHero.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    popularModels: {
        type: Object as PropType<People>,
        required: true,
    },
    popularMovies: {
        type: Object as PropType<Movies>,
        required: true,
    },
    latestMovies: {
        type: Object as PropType<Movies>,
        required: true,
    },
    recentlyUpdatedMovies: {
        type: Object as PropType<Movies>,
        required: true,
    },
    recentlyReleasedMovies: {
        type: Object as PropType<Movies>,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
    modelCount: {
        type: Number,
        required: true,
    },
    tagCount: {
        type: Number,
        required: true,
    },
    topUsers: {
        type: Object as PropType<Users>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <welcome-hero
        :model-count="props.modelCount"
        :movie-count="props.movieCount"
        :tag-count="props.tagCount"
    />

    <v-container class="p-4">
        <v-row>
            <v-col>
                <h1 class="mb-8 mt-4 text-2xl font-bold">
                    {{ $t('welcome.recentlyReleasedMovies') }}
                </h1>

                <card-swiper :items="props.recentlyReleasedMovies" />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h1 class="mb-8 mt-4 text-2xl font-bold">
                    {{ $t('welcome.popularModels') }}
                </h1>

                <card-swiper :items="props.popularModels" />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h1 class="mb-8 mt-4 text-2xl font-bold">
                    {{ $t('welcome.popularMovies') }}
                </h1>

                <card-swiper :items="props.popularMovies" />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h1 class="mb-8 mt-4 text-2xl font-bold">
                    {{ $t('welcome.recentlyAddedMovies') }}
                </h1>

                <card-swiper :items="props.latestMovies" />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <h1 class="mb-8 mt-4 text-2xl font-bold">
                    {{ $t('welcome.recentlyUpdatedMovies') }}
                </h1>

                <card-swiper :items="props.recentlyUpdatedMovies" />
            </v-col>
        </v-row>

        <leaderboard :top-users="props.topUsers" />
    </v-container>
</template>
