<script setup lang="ts">
import type { Paginated, Studio } from '@/types/models';
import type { PropType } from 'vue';

import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import SideMenu from '@/Components/SideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    studios: {
        type: Object as PropType<Paginated<Studio>>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const loading = ref(false);
</script>

<template>
    <Head title="Studios" />

    <side-menu-page>
        <template #left>
            <side-menu title="Filter" />
        </template>

        <template #right>
            <paginated-item-grid
                v-model:loading="loading"
                :items="props.studios"
            />
        </template>
    </side-menu-page>
</template>
