<script setup lang="ts">
import type { Paginated } from '@/types/models';
import type { PropType } from 'vue';

import { router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useDisplay } from 'vuetify';

import MdiChevronLeft from '~icons/mdi/chevron-left';
import MdiChevronRight from '~icons/mdi/chevron-right';

import useRouteStore from '@/stores/route';

const props = defineProps({
    modelValue: {
        type: Number,
        required: true,
    },
    paginatedData: {
        type: Object as PropType<Paginated>,
        required: true,
    },
    loading: {
        type: Boolean,
    },
});

const emit = defineEmits<{
    (event: 'update:loading', value: boolean): void;
    (event: 'update:modelValue', value: number): void;
}>();

const routeStore = useRouteStore();

const currentRoute = computed(() => {
    return routeStore.current;
});

const routeParams = computed(() => {
    return routeStore.params;
});

function updatePage(page: number) {
    emit('update:loading', true);

    router.get(
        route(currentRoute.value, {
            ...routeParams.value,
            page,
        }),
        undefined,
        {
            preserveScroll: true,
            preserveState: true,
            onStart: () => {
                emit('update:modelValue', page);
            },
            onFinish: () => {
                emit('update:loading', false);
            },
        },
    );
}

const { smAndDown, md, lg, xl, xxl } = useDisplay();

const totalVisible = computed(() => {
    if (smAndDown.value) {
        return 3;
    }

    if (md.value) {
        return 5;
    }

    if (lg.value) {
        return 7;
    }

    if (xl.value) {
        return 9;
    }

    if (xxl.value) {
        return 11;
    }

    return 5;
});
</script>

<template>
    <v-pagination
        :model-value="props.modelValue"
        :length="paginatedData.last_page"
        :prev-icon="MdiChevronLeft"
        :next-icon="MdiChevronRight"
        :total-visible="totalVisible"
        :disabled="props.loading"
        @update:model-value="updatePage"
    />
</template>
