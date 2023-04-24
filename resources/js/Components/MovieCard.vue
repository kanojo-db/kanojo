<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import { useDisplay } from 'vuetify';

import IonWarning from '~icons/ion/warning';
import MdiCheck from '~icons/mdi/check';
import MdiHeart from '~icons/mdi/heart';
import MdiHelp from '~icons/mdi/help';
import MdiStar from '~icons/mdi/star';
import MdiVideo3d from '~icons/mdi/video-3d';
import MdiVirtualReality from '~icons/mdi/virtual-reality';

import { useTitle } from '@/utils/item';

import UserRating from './UserRating.vue';

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const title = useTitle(props.movie, locale);

const { mdAndUp } = useDisplay();
</script>

<template>
    <Link
        class="relative block aspect-[1/1.95] w-full overflow-hidden hover:text-primary dark:hover:text-secondary"
        :href="route('movies.show', { movie: props.movie.slug })"
    >
        <div class="flex flex-col flex-nowrap place-items-start">
            <v-img
                v-if="
                    props.movie.poster ||
                    (props.movie.is_vr && (props.movie as Movie).fanart)
                "
                :src="
                    props.movie.is_vr
                        ? (props.movie as Movie).fanart?.original_url
                        : props.movie.poster?.original_url
                "
                :alt="`Poster for ${title}`"
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
                            :width="mdAndUp ? 128 : 64"
                            :height="mdAndUp ? 128 : 64"
                        />
                    </div>
                </template>
            </v-img>

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

            <div class="absolute left-0 top-0 flex flex-row gap-2 p-2">
                <div
                    v-if="props.movie.in_collection"
                    class="bg-tertiary rounded-full p-1 text-stone-50 shadow-md dark:text-stone-950"
                >
                    <mdi-check class="h-4 w-4" />
                </div>

                <div
                    v-if="props.movie.in_wishlist"
                    class="bg-tertiary rounded-full p-1 text-stone-50 shadow-md dark:text-stone-950"
                >
                    <mdi-heart class="h-4 w-4" />
                </div>

                <div
                    v-if="props.movie.in_favorites"
                    class="bg-tertiary rounded-full p-1 text-stone-50 shadow-md dark:text-stone-950"
                >
                    <mdi-star class="h-4 w-4" />
                </div>
            </div>

            <div class="absolute right-0 top-0 flex flex-row gap-2 p-2">
                <div
                    v-if="props.movie.is_3d"
                    class="bg-tertiary rounded-full p-1 text-stone-50 shadow-md dark:text-stone-950"
                >
                    <mdi-video-3d class="h-4 w-4" />
                </div>

                <div
                    v-if="props.movie.is_vr"
                    class="bg-tertiary rounded-full p-1 text-stone-50 shadow-md dark:text-stone-950"
                >
                    <mdi-virtual-reality class="h-4 w-4" />
                </div>
            </div>

            <div
                class="relative flex flex-col flex-nowrap place-items-start pt-2 md:-top-16"
            >
                <user-rating
                    class="ml-2 hidden rounded-full bg-stone-50 text-stone-950 dark:bg-stone-950 dark:text-stone-50 md:flex"
                    :size="48"
                    :width="4"
                    :reactable="movie"
                />

                <span
                    class="mt-4 line-clamp-2 overflow-hidden text-ellipsis text-sm font-bold md:text-base"
                >
                    {{ title }}
                </span>
            </div>
        </div>
    </Link>
</template>
