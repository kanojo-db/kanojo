<script setup lang="ts">
import type { Item, Movie } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiChevronLeft from '~icons/mdi/chevron-left';
import MdiHelp from '~icons/mdi/help';

import {
    isMovie,
    isPerson,
    isSeries,
    isStudio,
    useItemRouteName,
    useItemRouteParams,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

const { locale } = useI18n();

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const routeName = useItemRouteName(props.item);

const routeParam = useItemRouteParams(props.item);
</script>

<template>
    <v-container class="flex flex-row gap-4 p-4">
        <div class="max-w-[300px]">
            <v-img
                v-if="
                    (isMovie(props.item) || isPerson(props.item)) &&
                    (props.item.poster ||
                        (isMovie(props.item) &&
                            props.item?.is_vr &&
                            props.item.fanart))
                "
                :src="
                    isMovie(props.item) && props.item?.is_vr
                        ? (props.item as Movie).fanart?.original_url
                        : props.item.poster?.original_url
                "
                class="w-24 rounded bg-stone-200 shadow-md dark:bg-stone-800"
                cover
                :aspect-ratio="1 / 1.5"
                :aria-label="title"
            >
                <template #placeholder>
                    <div
                        class="relative flex aspect-[1/1.5] w-24 place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
                    >
                        <mdi-help
                            class="h-20 w-20 text-stone-300 dark:text-stone-700"
                            color="currentColor"
                        />
                    </div>
                </template>
            </v-img>

            <div
                v-else-if="!isStudio(props.item)"
                class="relative flex aspect-[1/1.5] w-24 place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
            >
                <mdi-help
                    class="h-20 w-20 text-stone-300 dark:text-stone-700"
                    color="currentColor"
                />
            </div>
        </div>

        <div class="flex h-full flex-col place-items-start">
            <h1 class="mb-2 line-clamp-2 text-ellipsis text-2xl font-extrabold">
                {{ title }}
            </h1>

            <Link
                class="flex"
                :href="route(`${routeName}.show`, routeParam)"
            >
                <mdi-chevron-left class="h-6 w-6" />

                {{ $t('general.backToOverview') }}
            </Link>
        </div>
    </v-container>
</template>
