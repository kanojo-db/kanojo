<script setup lang="ts">
import type { Studio } from '@/types/models';
import type { PropType } from 'vue';

import { DateTime } from 'luxon';
import { ref } from 'vue';

import CardSwiper from '@/Components/CardSwiper.vue';
import PaginatedItemGrid from '@/Components/PaginatedItemGrid.vue';
import TwoColumnInfo from '@/Components/TwoColumnInfo.vue';

import ItemLinks from './ItemLinks.vue';

const props = defineProps({
    item: {
        type: Object as PropType<Studio>,
        required: true,
    },
});

const loading = ref(false);
</script>

<template>
    <two-column-info>
        <template #left>
            <h2 class="mb-2 block text-lg font-bold md:hidden">
                {{ $t('studio.releasedMovies') }}
            </h2>

            <paginated-item-grid
                v-model:loading="loading"
                :items="item.movies"
            />
        </template>

        <template #right>
            <span class="block text-lg font-bold">
                {{ $t('studio.foundedOn') }}
            </span>

            <meta
                v-if="props.item.founded_date"
                itemprop="foundingDate"
                :content="props.item.founded_date"
            />

            {{
                props.item.founded_date
                    ? DateTime.fromISO(props.item.founded_date).toLocaleString(
                          DateTime.DATE_SHORT,
                      )
                    : $t('general.unknown')
            }}

            <span class="block text-lg font-bold">
                {{ $t('studio.logo') }}
            </span>

            <v-card
                v-if="props.item.logo"
                class="bg-checkered my-2 h-min w-full border-stone-400 bg-stone-50 p-3"
                theme="light"
                variant="outlined"
                :href="props.item.logo.original_url"
            >
                <meta
                    itemprop="logo"
                    :content="props.item.logo.original_url"
                />

                <img
                    alt="Logo"
                    :src="props.item.logo.original_url"
                />
            </v-card>

            <h2 class="block text-lg font-bold">
                {{ $t('general.links') }}
            </h2>

            <item-links :item="props.item" />
        </template>
    </two-column-info>

    <div class="bg-stone-300 dark:bg-stone-700">
        <v-container
            v-if="props.item.series.length > 0"
            class="relative"
        >
            <h2 class="mb-2 text-xl font-bold">
                {{ $t('studio.mostActiveSeries') }}
            </h2>

            <card-swiper :items="props.item.series" />
        </v-container>
    </div>
</template>
