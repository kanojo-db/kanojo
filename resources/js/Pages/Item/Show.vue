<script async setup lang="ts">
import type { Item, Movie, Person, Series, Studio } from '@/types/models';
import type { Palette, Swatch } from '@vibrant/color';
import type { Component, PropType } from 'vue';

import { Head } from '@inertiajs/vue3';
import Vibrant from 'node-vibrant/lib/bundle';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useTheme } from 'vuetify';

import MdiHelp from '~icons/mdi/help';

import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import MovieDetailsHeader from '@/Components/MovieDetailsHeader.vue';
import MovieDetailsInfo from '@/Components/MovieDetailsInfo.vue';
import PersonDetailsHeader from '@/Components/PersonDetailsHeader.vue';
import PersonDetailsInfo from '@/Components/PersonDetailsInfo.vue';
import SeriesDetailsHeader from '@/Components/SeriesDetailsHeader.vue';
import SeriesDetailsInfo from '@/Components/SeriesDetailsInfo.vue';
import StudioDetailsHeader from '@/Components/StudioDetailsHeader.vue';
import StudioDetailsInfo from '@/Components/StudioDetailsInfo.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    isMovie as isMovieFunction,
    isPerson as isPersonFunction,
    isSeries as isSeriesFunction,
    isStudio as isStudioFunction,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

function isMovie(item: Item): item is Movie {
    return isMovieFunction(item);
}

function isPerson(item: Item): item is Person {
    return isPersonFunction(item);
}

function isStudio(item: Item): item is Studio {
    return isStudioFunction(item);
}

function isSeries(item: Item): item is Series {
    return isSeriesFunction(item);
}

const { locale } = useI18n();

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const vibrant = ref<Swatch | null>(null);
const darkVibrant = ref<Swatch | null>(null);

const theme = useTheme();

if (
    (isMovie(props.item) || isPerson(props.item)) &&
    props.item?.poster &&
    !import.meta.env.SSR
) {
    const VibrantInstance = Vibrant.from(props.item.poster.original_url);

    VibrantInstance.getPalette().then((palette: Palette) => {
        vibrant.value = palette.Vibrant;
        darkVibrant.value = palette.DarkVibrant;
    });
}

const headerColor = computed(() => {
    if (theme.current.value.dark) {
        return darkVibrant;
    }

    return vibrant;
});

let headerComponent: Component;

if (isMovie(props.item)) {
    headerComponent = MovieDetailsHeader;
}

if (isPerson(props.item)) {
    headerComponent = PersonDetailsHeader;
}

if (isStudio(props.item)) {
    headerComponent = StudioDetailsHeader;
}

if (isSeries(props.item)) {
    headerComponent = SeriesDetailsHeader;
}

let detailsComponent: Component;

if (isMovie(props.item)) {
    detailsComponent = MovieDetailsInfo;
}

if (isPerson(props.item)) {
    detailsComponent = PersonDetailsInfo;
}

if (isStudio(props.item)) {
    detailsComponent = StudioDetailsInfo;
}

if (isSeries(props.item)) {
    detailsComponent = SeriesDetailsInfo;
}

const shouldShowImage = computed(() => {
    return !isStudio(props.item);
});
</script>

<template>
    <Head :title="title">
        <!-- OpenGraph -->
        <meta
            property="og:title"
            :content="title"
        />

        <meta
            v-if="isMovie(props.item) || isPerson(props.item)"
            property="og:image"
            :content="props.item.social_image"
        />

        <meta
            v-if="isMovie(props.item) || isPerson(props.item)"
            property="og:image:width"
            content="1200"
        />

        <meta
            v-if="isMovie(props.item) || isPerson(props.item)"
            property="og:image:height"
            content="630"
        />
    </Head>

    <item-info-page
        :item="props.item"
        :header-color="headerColor?.value?.hex"
    >
        <template #header>
            <v-container>
                <v-row>
                    <v-col
                        v-if="shouldShowImage"
                        :cols="6"
                        :sm="5"
                        :md="5"
                        :lg="3"
                        :xl="2"
                    >
                        <v-img
                            v-if="
                                (isMovie(props.item) || isPerson(props.item)) &&
                                (props.item.poster ||
                                    (isMovie(props.item) &&
                                        props.item.is_vr &&
                                        props.item.fanart))
                            "
                            :src="
                                isMovie(props.item) && props.item?.is_vr
                                    ? (props.item as Movie).fanart?.original_url
                                    : props.item.poster?.original_url
                            "
                            itemprop="image"
                            :content="
                                isMovie(props.item) && props.item?.is_vr
                                    ? (props.item as Movie).fanart?.original_url
                                    : props.item.poster?.original_url
                            "
                            class="rounded bg-stone-200 shadow-md dark:bg-stone-800"
                            cover
                            :aspect-ratio="1 / 1.5"
                            :label="title"
                        >
                            <template #placeholder>
                                <div
                                    class="flex aspect-[1/1.5] place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
                                >
                                    <mdi-help
                                        class="text-stone-300 dark:text-stone-700"
                                        color="currentColor"
                                        width="150px"
                                        height="150px"
                                    />
                                </div>
                            </template>
                        </v-img>

                        <div
                            v-else
                            class="flex aspect-[1/1.5] place-items-center justify-center rounded bg-stone-200 shadow-md dark:bg-stone-800"
                        >
                            <mdi-help
                                class="text-stone-300 dark:text-stone-700"
                                color="currentColor"
                                width="150px"
                                height="150px"
                            />
                        </div>
                    </v-col>

                    <v-col
                        :cols="12"
                        :lg="shouldShowImage ? 9 : 12"
                        :xl="shouldShowImage ? 10 : 12"
                    >
                        <component
                            :is="headerComponent"
                            :item="item"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </template>

        <template #default>
            <component
                :is="detailsComponent"
                :item="item"
            />
        </template>
    </item-info-page>
</template>
