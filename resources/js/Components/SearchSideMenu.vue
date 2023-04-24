<script setup lang="ts">
import type { PropType } from 'vue';

import { ref } from 'vue';

import SideMenu from '@/Components/SideMenu.vue';

const props = defineProps({
    currentItemType: {
        type: String,
        required: true,
    },
    counts: {
        type: Object as PropType<{
            movies: number;
            people: number;
            series: number;
            studios: number;
        }>,
        required: true,
    },
});

const validSearchTypes = ref([
    {
        name: 'Movies',
        type: 'movies',
        count: props.counts.movies,
    },
    {
        name: 'People',
        type: 'people',
        count: props.counts.people,
    },
    {
        name: 'Series',
        type: 'series',
        count: props.counts.series,
    },
    {
        name: 'Studios',
        type: 'studios',
        count: props.counts.studios,
    },
]);

const routeParams = route().params;
routeParams.page = 1;
</script>

<template>
    <side-menu title="Search">
        <v-list-item
            v-for="searchType in validSearchTypes"
            :key="searchType.type"
            :to="
                route(`search`, {
                    ...routeParams,
                    type: searchType.type,
                })
            "
            :active="searchType.type === currentItemType"
        >
            <span>
                {{ searchType.name }}
            </span>

            <template #append>
                <v-chip>
                    {{ searchType.count }}
                </v-chip>
            </template>
        </v-list-item>
    </side-menu>
</template>
