<script setup>
import { computed } from 'vue';

import MovieCard from '@/Components/MovieCard.vue';
import PersonTabBar from '@/Components/PersonTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    person: {
        type: Object,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
});

const posterUrl = computed(() => {
    if (props.person?.media && props.person.media.length > 0) {
        return props.person.media.filter(
            (m) => m.collection_name === 'profile',
        )?.[0].original_url;
    }

    return null;
});

const title = computed(() =>
    props.person.name.en ? props.person.name.en : props.person.name.jp,
);
</script>

<template>
    <AppLayout :title="title">
        <div class="col bg-grey-3">
            <PersonTabBar :person="person" />
            <div class="row q-py-lg q-px-md">
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="300px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders q-mr-lg"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center q-mr-lg"
                    style="width: 300px; height: 450px"
                >
                    <q-icon
                        name="mdi-help"
                        size="150px"
                        color="grey-2"
                    />
                </div>
                <div class="col">
                    <div class="col q-mb-sm">
                        <h1 class="text-h4 q-mt-none q-mb-sm">
                            {{
                                person.name.en ? person.name.en : person.name.jp
                            }}
                            <q-badge
                                class="text-subtitle1"
                                align="middle"
                                color="grey-7"
                            >
                                {{
                                    $t('web.personShow.moviesCount', {
                                        number: movieCount,
                                    })
                                }}
                            </q-badge>
                        </h1>
                        <span
                            v-if="person.name.en"
                            class="text-subtitle1"
                        >
                            {{ person.name.jp }}
                        </span>
                    </div>
                    <div class="row justify-start items-start">
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.birth_date') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.birthdate ??
                                    $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong> {{ $t('web.model.active') }} </strong>
                                <br />
                                <span v-if="person.career_start">
                                    {{ person.career_start }} -
                                    {{
                                        person.career_end ??
                                        $t('web.general.present')
                                    }}
                                </span>
                                <span v-else>{{
                                    $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.country') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.country ?? $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.blood_type') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.blood_type ??
                                    $t('web.general.unknown')
                                }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.height') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.height
                                        ? `${person.height}cm`
                                        : $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.bust') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.bust
                                        ? `${person.bust}cm`
                                        : $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.waist') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.waist
                                        ? `${person.waist}cm`
                                        : $t('web.general.unknown')
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.hips') }}
                                </strong>
                                <br />
                                <span>{{
                                    person.hip
                                        ? `${person.hip}cm`
                                        : $t('web.general.unknown')
                                }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>
                                    {{ $t('web.model.cup_size') }}
                                </strong>
                                <br />
                                <span>
                                    {{
                                        person.cup_size ??
                                        $t('web.general.unknown')
                                    }}
                                    <template
                                        v-if="person.breast_implants !== null"
                                    >
                                        {{
                                            person.breast_implants
                                                ? $t(
                                                      'web.model.breasts_implant',
                                                  )
                                                : $t(
                                                      'web.model.breasts_natural',
                                                  )
                                        }}
                                    </template>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <div
                class="fit row wrap justify-start items-start content-start q-gutter-md"
            >
                <MovieCard
                    v-for="movie in person.movies"
                    :key="movie.id"
                    :movie="movie"
                />
            </div>
        </div>
    </AppLayout>
</template>
