<script setup lang="ts">
import type { Item, Paginated, Person } from '@/types/models';
import type { PropType } from 'vue';

import { ref } from 'vue';

import MovieCard from '@/Components/MovieCard.vue';
import { isMovie, isPerson, isSeries, isStudio } from '@/utils/item';

import ModelCard from './ModelCard.vue';
import Pagination from './Pagination.vue';
import SeriesCard from './SeriesCard.vue';
import StudioCard from './StudioCard.vue';

interface PersonWithMovieCount extends Person {
    movies_count: number;
}

const props = defineProps({
    items: {
        type: Object as PropType<Paginated<Item>>,
        required: true,
    },
    loading: {
        type: Boolean,
    },
});

const emit = defineEmits<{
    (event: 'update:loading', value: boolean): void;
}>();

const currentPage = ref(props.items.current_page);
</script>

<template>
    <div class="mb-4 flex flex-row justify-center">
        <pagination
            v-model="currentPage"
            :paginated-data="props.items"
            :loading="props.loading"
            @update:loading="emit('update:loading', $event)"
        />
    </div>

    <div
        v-if="props.loading"
        class="relative flex h-96 flex-row items-center justify-center"
    >
        <v-progress-circular
            indeterminate
            color="primary"
        />
    </div>

    <div
        v-if="!props.loading && props.items.data.length > 0"
        class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-7 xxl:grid-cols-9"
    >
        <div
            v-for="item in props.items.data"
            :key="item.id"
        >
            <movie-card
                v-if="isMovie(item)"
                :movie="item"
            />

            <series-card
                v-else-if="isSeries(item)"
                :series="item"
            />

            <studio-card
                v-else-if="isStudio(item)"
                :studio="item"
            />

            <model-card
                v-else-if="isPerson(item)"
                :model="item as PersonWithMovieCount"
            />
        </div>
    </div>

    <div v-if="!props.loading && props.items.data.length === 0">
        <slot>
            <p>{{ $t('item.pagination.noItems') }}</p>
        </slot>
    </div>

    <div class="mt-4 flex flex-row justify-center">
        <pagination
            v-model="currentPage"
            :paginated-data="props.items"
            :loading="props.loading"
            @update:loading="emit('update:loading', $event)"
        />
    </div>
</template>
