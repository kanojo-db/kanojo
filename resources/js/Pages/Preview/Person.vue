<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { DateTime } from 'luxon';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiGenderFemale from '~icons/mdi/gender-female?width=64&height=64';
import MdiGenderMale from '~icons/mdi/gender-male?width=64&height=64';
import MdiNonBinary from '~icons/mdi/gender-non-binary?width=64&height=64';
import MdiTransgender from '~icons/mdi/gender-transgender?width=64&height=64';
import MdiHelp from '~icons/mdi/help?width=64&height=64';

import MovieCard from '@/Components/MovieCard.vue';
import { getName } from '@/utils/item';

const props = defineProps({
    model: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const { locale } = useI18n();

const name = getName(props.model, locale.value);

const genderIcon = computed(() => {
    switch (props.model.gender?.id) {
        case 1:
            return MdiGenderFemale;
        case 2:
            return MdiGenderMale;
        case 3:
        case 4:
            return MdiTransgender;
        case 5:
            return MdiNonBinary;
        default:
            return MdiHelp;
    }
});
</script>

<template>
    <v-app>
        <div
            class="flex items-center justify-center overflow-hidden bg-stone-50"
        >
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
                            v-if="props.model.poster"
                            :src="props.model.poster?.original_url"
                            :content="props.model.poster?.original_url"
                            class="mx-10 my-4 rounded bg-stone-200 shadow-md dark:bg-stone-800"
                            cover
                            :aspect-ratio="1 / 1.5"
                            :label="name"
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
                                <div
                                    class="flex flex-row items-center justify-start gap-4"
                                >
                                    <h1
                                        class="line-clamp-1 text-ellipsis text-5xl font-black"
                                    >
                                        {{ name }}
                                    </h1>

                                    <v-icon
                                        :icon="genderIcon"
                                        class="h-8 w-8"
                                    />
                                </div>

                                <div class="flex flex-row gap-4">
                                    <h2
                                        class="line-clamp-1 text-ellipsis text-3xl font-bold"
                                    >
                                        {{
                                            getName(props.model, 'ja-JP', false)
                                        }}
                                    </h2>

                                    <v-chip
                                        color="primary"
                                        size="x-large"
                                    >
                                        {{
                                            $t('personShow.moviesCount', {
                                                number: props.model.movie_count.toLocaleString(),
                                            })
                                        }}
                                    </v-chip>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-[1fr_1fr_1fr_1fr] gap-2 text-2xl"
                            >
                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.birth_date') }}
                                    </strong>

                                    <br />

                                    <span>
                                        {{
                                            props.model.birthdate
                                                ? DateTime.fromISO(
                                                      props.model.birthdate,
                                                  ).toLocaleString(
                                                      DateTime.DATE_MED,
                                                  )
                                                : $t('general.unknown')
                                        }}
                                    </span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.country') }}
                                    </strong>

                                    <br />

                                    <span>
                                        {{
                                            props.model.country?.name ??
                                            $t('general.unknown')
                                        }}
                                    </span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.blood_type') }}
                                    </strong>

                                    <br />

                                    <span>{{
                                        props.model.blood_type ??
                                        $t('general.unknown')
                                    }}</span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.height') }}
                                    </strong>

                                    <br />

                                    <span>{{
                                        props.model.height
                                            ? $t('model.unit_cm', {
                                                  number: props.model.height.toLocaleString(),
                                              })
                                            : $t('general.unknown')
                                    }}</span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.bust') }}
                                    </strong>

                                    <br />

                                    <span>{{
                                        props.model.bust
                                            ? $t('model.unit_cm', {
                                                  number: props.model.bust.toLocaleString(),
                                              })
                                            : $t('general.unknown')
                                    }}</span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.waist') }}
                                    </strong>

                                    <br />

                                    <span>{{
                                        props.model.waist
                                            ? $t('model.unit_cm', {
                                                  number: props.model.waist.toLocaleString(),
                                              })
                                            : $t('general.unknown')
                                    }}</span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.hips') }}
                                    </strong>

                                    <br />

                                    <span>{{
                                        props.model.hip
                                            ? $t('model.unit_cm', {
                                                  number: props.model.hip.toLocaleString(),
                                              })
                                            : $t('general.unknown')
                                    }}</span>
                                </div>

                                <div class="xl:w-48">
                                    <strong>
                                        {{ $t('model.cup_size') }}
                                    </strong>

                                    <br />

                                    <span>
                                        {{
                                            props.model.cup_size ??
                                            $t('general.unknown')
                                        }}
                                    </span>
                                </div>
                            </div>

                            <div
                                v-if="props.model.movies"
                                class="flex flex-col gap-2"
                            >
                                <span class="text-3xl font-bold">
                                    {{ $t('previews.person.recentMovies') }}
                                </span>

                                <div class="flex flex-row gap-4">
                                    <div
                                        v-for="movie in props.model.movies"
                                        :key="movie.id"
                                        class="w-32"
                                    >
                                        <movie-card
                                            :movie="movie"
                                            hide-text
                                        />
                                    </div>
                                </div>
                            </div>

                            <div class="absolute bottom-0 right-0">
                                <img
                                    alt="Kanojo"
                                    src="/images/logo-light-color.svg"
                                    class="mb-5 mr-8 h-16 w-48 object-contain"
                                />
                            </div>
                        </div>
                    </v-col>
                </v-row>
            </v-container>
        </div>
    </v-app>
</template>

<style lang="scss">
body {
    height: 100%;
}
</style>
