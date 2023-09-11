<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { DateTime, Duration } from 'luxon';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import ModelCard from '@/Components/ModelCard.vue';
import { getName, getTitle } from '@/utils/item';

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const { locale } = useI18n();

const title = getTitle(props.movie, locale.value);

const movieDuration = computed(() => {
    if (!props.movie.length) {
        return null;
    }

    return Duration.fromObject({ minutes: props.movie.length });
});
</script>

<template>
    <div class="flex items-center justify-center overflow-hidden bg-stone-50">
        <v-container
            fluid
            class="h-100 p-0"
        >
            <v-row
                no-gutters
                class="h-100"
            >
                <div
                    class="mr-4 flex w-[37%] items-center justify-center bg-primary"
                >
                    <v-img
                        v-if="
                            props.movie.poster ||
                            (props.movie.is_vr && props.movie.fanart)
                        "
                        :src="
                            props.movie?.is_vr
                                ? props.movie.fanart?.original_url
                                : props.movie.poster?.original_url
                        "
                        itemprop="image"
                        :content="
                            props.movie?.is_vr
                                ? (props.movie as Movie).fanart?.original_url
                                : props.movie.poster?.original_url
                        "
                        class="mx-10 my-4 rounded bg-stone-200 shadow-md dark:bg-stone-800"
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
                </div>

                <v-col class="flex items-start pt-10">
                    <div class="flex flex-col gap-2">
                        <div class="flex flex-col gap-2">
                            <h1
                                class="line-clamp-1 text-ellipsis text-4xl font-black"
                            >
                                {{ title }}
                            </h1>

                            <div
                                class="flex flex-row content-start items-center justify-items-start gap-4"
                            >
                                <v-chip
                                    color="primary"
                                    size="large"
                                >
                                    {{ props.movie.type.name }}
                                </v-chip>

                                <v-chip
                                    v-if="props.movie.is_vr"
                                    color="primary"
                                    size="large"
                                >
                                    {{ $t('item.vr') }}
                                </v-chip>

                                <v-chip
                                    v-if="props.movie.is_3d"
                                    color="primary"
                                    size="large"
                                >
                                    {{ $t('item.3d') }}
                                </v-chip>

                                <span
                                    v-if="props.movie.length"
                                    class="text-2xl font-bold"
                                >
                                    {{
                                        movieDuration?.toHuman({
                                            unitDisplay: 'short',
                                        })
                                    }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="grid grid-cols-[1fr_1fr_1fr_1fr] gap-2 text-2xl"
                        >
                            <div class="xl:w-48">
                                <strong>
                                    {{ $t('previews.movie.firstReleasedOn') }}
                                </strong>

                                <br />

                                <span>
                                    {{
                                        props.movie.release_date
                                            ? DateTime.fromISO(
                                                  props.movie.release_date,
                                              ).toLocaleString(
                                                  DateTime.DATE_MED,
                                              )
                                            : $t('general.unknown')
                                    }}
                                </span>
                            </div>

                            <div class="xl:w-48">
                                <strong>
                                    {{ $t('previews.movie.studio') }}
                                </strong>

                                <br />

                                <span>
                                    {{
                                        props.movie.studio
                                            ? getName(
                                                  props.movie.studio,
                                                  locale,
                                              )
                                            : $t('general.unknown')
                                    }}
                                </span>
                            </div>

                            <div class="xl:w-48" />

                            <div class="xl:w-48" />

                            <div
                                v-if="props.movie.series"
                                class="col-span-4 flex flex-col text-2xl"
                            >
                                <span class="font-bold">
                                    {{ $t('previews.movie.series') }}
                                </span>

                                {{ getTitle(props.movie.series, locale) }}
                            </div>
                        </div>

                        <div
                            v-if="props.movie.models"
                            class="flex flex-col gap-2"
                        >
                            <span class="text-3xl font-bold">
                                {{ $t('previews.movie.starring') }}
                            </span>

                            <div class="flex flex-row gap-4">
                                <div
                                    v-for="model in props.movie.models.slice(
                                        0,
                                        5,
                                    )"
                                    :key="model.id"
                                    class="w-32"
                                >
                                    <model-card
                                        :model="model"
                                        text-class="text-xl"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="absolute bottom-0 right-0">
                            <img
                                alt="Kanojo"
                                src="/images/logo-light-color.svg"
                                class="mb-3 mr-6 h-16 w-48 object-contain"
                            />
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<style lang="scss">
body {
    height: 100%;
}
</style>
