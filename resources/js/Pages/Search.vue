<script setup lang="ts">
import type { Item, Paginated } from '@/types/models';
import type { PropType } from 'vue';

import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import SearchSideMenu from '@/Components/SearchSideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

interface SearchParams {
    page: number;
    q: string;
    type: string;
}

const props = defineProps({
    items: {
        type: Object as PropType<Paginated<Item>>,
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

defineOptions({
    layout: AppLayout,
});

const routeParams = route().params as unknown as SearchParams;

const resultType = computed(() => {
    if (routeParams.type === 'people') {
        return 'people';
    }

    if (routeParams.type === 'studios') {
        return 'studios';
    }

    if (routeParams.type === 'series') {
        return 'series';
    }

    // Default to movies if no type is specified
    return 'movies';
});

const loading = ref(false);
</script>

<template>
    <Head title="Search" />

    <side-menu-page>
        <template #left>
            <search-side-menu
                :current-item-type="resultType"
                :counts="props.counts"
            />
        </template>

        <template #right>
            <paginated-item-grid
                v-model:loading="loading"
                :items="props.items"
            >
                <p>{{ $t('search.no_results') }}</p>
            </paginated-item-grid>
        </template>
    </side-menu-page>
</template>
