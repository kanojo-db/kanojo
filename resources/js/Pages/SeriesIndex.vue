<script setup lang="ts">
import type { Paginated, Series } from '@/types/models';
import type { PropType } from 'vue';

import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import SideMenu from '@/Components/SideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    series: {
        type: Object as PropType<Paginated<Series>>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const sortBy = ref(route().params?.sort || 'created_at');

const currentPage = ref(props.series.current_page);
const loading = ref(false);

function applyFilters() {
    loading.value = true;

    let changePage = false;

    const params = {
        page: currentPage.value,
    };

    if (changePage) {
        params.page = 1;
    }

    if (sortBy.value) {
        params.sort = sortBy.value;
    }

    router.get(
        route('series.index'),
        { ...params },
        {
            only: ['series'],
            preserveState: true,
            preserveScroll: true,
            onFinish: () => {
                loading.value = false;
            },
        },
    );
}
</script>

<template>
    <Head title="Series" />

    <side-menu-page>
        <template #left>
            <side-menu title="Filter">
                <v-list-item>
                    <v-list-item-title class="mb-4 font-bold">
                        {{ $t('general.sortBy') }}
                    </v-list-item-title>

                    <v-select
                        v-model="sortBy"
                        :items="[
                            { value: 'name', name: 'Name' },
                            { value: '-name', name: 'Name (desc)' },
                            { value: 'movies', name: 'Number of Movies' },
                            {
                                value: '-movies',
                                name: 'Number of Movies (desc)',
                            },
                            { value: 'created_at', name: 'Creation Date' },
                            {
                                value: '-created_at',
                                name: 'Creation Date (desc)',
                            },
                            { value: 'updated_at', name: 'Last Update' },
                            {
                                value: '-updated_at',
                                name: 'Last Update (desc)',
                            },
                        ]"
                        item-value="value"
                        item-title="name"
                        hide-details
                        :disabled="loading"
                        @update:model-value="applyFilters"
                    />
                </v-list-item>
            </side-menu>
        </template>

        <template #right>
            <paginated-item-grid
                v-model:loading="loading"
                :items="props.series"
            />
        </template>
    </side-menu-page>
</template>
