<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import IonWarning from '~icons/ion/warning';
import MdiHelp from '~icons/mdi/help';

import { useName } from '@/utils/item';

const props = defineProps({
    model: {
        type: Object as PropType<Person & { movies_count: number }>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.model, locale);
</script>

<template>
    <Link
        class="relative block aspect-[1/1.85] w-full overflow-hidden hover:text-primary dark:hover:text-secondary"
        :href="route('models.show', { model: props.model.slug })"
    >
        <div class="flex flex-col flex-nowrap place-items-start">
            <v-img
                v-if="props.model.poster"
                :src="props.model.poster.original_url"
                :alt="`Profile image for ${name}`"
                class="w-full rounded bg-stone-200 shadow-md dark:bg-stone-800"
                :aspect-ratio="1 / 1.5"
                cover
            >
                <template #error>
                    <div
                        class="flex aspect-[1/1.5] w-full flex-col place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
                    >
                        <ion-warning
                            class="text-stone-300 dark:text-stone-700"
                            color="currentColor"
                            :width="128"
                            :height="128"
                        />
                    </div>
                </template>
            </v-img>

            <div
                v-else
                class="flex aspect-[1/1.5] w-full place-items-center justify-center rounded bg-stone-200 drop-shadow-md dark:bg-stone-800"
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
                    {{ name }}
                </span>

                <span class="mb-2">
                    {{
                        $t('general.movieCount', {
                            count: props.model.movies_count,
                        })
                    }}
                </span>
            </div>
        </div>
    </Link>
</template>
