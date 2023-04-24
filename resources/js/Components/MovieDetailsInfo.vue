<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import ModelRoleCard from '@/Components/ModelRoleCard.vue';
import { getName, getTitle, isMovie } from '@/utils/item';

import ItemLinks from './ItemLinks.vue';
import TwoColumnInfo from './TwoColumnInfo.vue';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const { locale } = useI18n();

const title = isMovie(props.item)
    ? getTitle(props.item, locale.value)
    : getName(props.item, locale.value);

const studioName = props.item?.studio
    ? getName(props.item.studio, locale.value)
    : null;
const seriesName = props.item?.series
    ? getTitle(props.item.series, locale.value)
    : null;
</script>

<template>
    <two-column-info>
        <template #left>
            <h2 class="mb-2 text-lg font-bold">
                {{ $t('movie.show.versions') }}
            </h2>

            <v-table
                class="rounded-lg bg-stone-200 shadow-md dark:bg-stone-700"
                density="compact"
                hover
            >
                <thead>
                    <tr>
                        <th scope="col">
                            {{ $t('movie.show.format') }}
                        </th>

                        <th scope="col">
                            {{ $t('movie.show.product_code') }}
                        </th>

                        <th scope="col">
                            {{ $t('movie.show.release_date') }}
                        </th>

                        <th scope="col">
                            {{ $t('movie.show.barcode') }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <template v-if="props.item?.versions?.length === 0">
                        <tr>
                            <td colspan="4">
                                {{ $t('movie.show.no_versions') }}
                            </td>
                        </tr>
                    </template>

                    <tr
                        v-for="version in props.item.versions"
                        :key="version.id"
                    >
                        <td>
                            {{ version.format }}
                        </td>

                        <td>
                            {{ version.product_code }}
                        </td>

                        <td>
                            {{ version.release_date }}
                        </td>

                        <td>
                            {{ version?.barcode }}
                        </td>
                    </tr>
                </tbody>
            </v-table>

            <h2 class="my-2 text-lg font-bold">
                {{ $t('movie.show.models') }}
            </h2>

            <div
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-3 xxl:grid-cols-5"
            >
                <p v-if="props.item?.models?.length === 0">
                    {{ $t('movie.show.noModels') }}
                </p>

                <model-role-card
                    v-for="model in props.item.models"
                    :key="`movie-model-${model.id}`"
                    :movie="item"
                    :model="model"
                />
            </div>

            <!--<h2 class="my-2 text-lg font-bold">
                {{ $t('movie.show.scenes') }}
            </h2>

            <v-table
                class="rounded-lg bg-stone-200 shadow-md dark:bg-stone-700"
                density="compact"
                hover
            >
                <thead>
                    <tr>
                        <th scope="col">Coming soon</th>
                    </tr>
                </thead>
            </v-table>-->

            <!--<h2 class="my-2 text-lg font-bold">
                {{ $t('movie.show.files') }}
            </h2>

            <v-table
                class="rounded-lg bg-stone-200 shadow-md dark:bg-stone-700"
                density="compact"
                hover
            >
                <thead>
                    <tr>
                        <th scope="col">Coming soon</th>
                    </tr>
                </thead>
            </v-table>-->
        </template>

        <template #right>
            <div
                v-if="title"
                class="mb-2"
            >
                <span class="block text-lg font-bold">
                    {{ $t('movie.show.original_title') }}
                </span>
                {{ props.item.title['ja-JP'] }}
            </div>

            <div
                v-if="seriesName"
                class="mb-2"
            >
                <span class="block text-lg font-bold">
                    {{ $t('movie.show.series') }}
                </span>

                <meta
                    itemprop="isPartOf"
                    :content="
                        route('series.show', { series: item?.series?.slug })
                    "
                />

                <Link
                    :href="route('series.show', { series: item?.series?.slug })"
                >
                    {{ seriesName }}
                </Link>
            </div>

            <div
                v-if="props.item.studio"
                class="mb-2"
                itemprop="productionCompany"
                itemscope
                itemtype="https://schema.org/Organization"
            >
                <span class="block text-lg font-bold">
                    {{ $t('movie.show.studio') }}
                </span>

                <meta
                    v-if="studioName"
                    itemprop="name"
                    :content="studioName"
                />

                <Link
                    v-if="props.item.studio"
                    :href="route('studios.show', item.studio)"
                >
                    {{ studioName }}
                </Link>
            </div>

            <h2 class="block text-lg font-bold">
                {{ $t('general.links') }}
            </h2>

            <item-links :item="props.item" />
        </template>
    </two-column-info>
</template>
