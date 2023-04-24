<script setup lang="ts">
import type { Studio } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiHelp from '~icons/mdi/help';

import { useName } from '@/utils/item';

const props = defineProps({
    studio: {
        type: Object as PropType<Studio>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.studio, locale);
</script>

<template>
    <Link
        class="relative block aspect-[1/1.1] w-full overflow-hidden hover:text-primary dark:hover:text-secondary"
        :href="route('studios.show', { studio: props.studio.slug })"
    >
        <div
            class="w-full rounded bg-stone-200 p-3 shadow-md dark:bg-stone-500"
        >
            <v-img
                v-if="props.studio.logo"
                :src="props.studio.logo.original_url"
                :alt="`Company logo for ${name}`"
                :aspect-ratio="4 / 3"
                loading="lazy"
            />

            <div
                v-else
                class="flex aspect-[4/3] place-items-center justify-center"
            >
                <mdi-help
                    class="text-stone-300 dark:text-stone-600"
                    color="currentColor"
                    :width="128"
                    :height="128"
                />
            </div>
        </div>

        <div class="relative flex flex-col flex-nowrap place-items-start">
            <span
                class="mt-3 line-clamp-2 overflow-hidden text-ellipsis font-bold"
            >
                {{ name }}
            </span>

            <span class="mb-2">
                {{
                    $t('general.movieCount', {
                        count: props.studio.movies_count,
                    })
                }}
            </span>
        </div>
    </Link>
</template>
