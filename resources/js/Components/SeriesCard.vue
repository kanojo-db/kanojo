<script setup lang="ts">
import type { Series } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiHelp from '~icons/mdi/help';

import { useTitle } from '@/utils/item';

const props = defineProps({
    series: {
        type: Object as PropType<Series>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const title = useTitle(props.series, locale);
</script>

<template>
    <Link
        class="relative block aspect-[1/1.85] w-full overflow-hidden hover:text-primary dark:hover:text-secondary"
        :href="route('series.show', { series: props.series.slug })"
    >
        <v-img
            v-if="props.series.poster"
            :src="props.series.poster.original_url"
            :alt="`Company logo for ${title}`"
            class="w-full rounded bg-stone-200 shadow-md dark:bg-stone-800"
            :aspect-ratio="1 / 1.5"
            loading="lazy"
        />

        <div
            v-else
            class="flex aspect-[1/1.5] w-full place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
        >
            <mdi-help
                class="text-stone-300 dark:text-stone-700"
                color="currentColor"
                :width="128"
                :height="128"
            />
        </div>

        <div class="relative flex flex-col flex-nowrap place-items-start">
            <span
                class="mt-3 line-clamp-1 overflow-hidden text-ellipsis font-bold"
            >
                {{ title }}
            </span>

            <span class="mb-2">
                {{
                    $t('general.movieCount', {
                        count: props.series.movies_count,
                    })
                }}
            </span>
        </div>
    </Link>
</template>
