<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MovieCard from '@/Components/MovieCard.vue';
import PersonTabBar from '@/Components/PersonTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useFirstImage, useName } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    person: {
        type: Object,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
    movies: {
        type: Array,
        required: true,
    },
});

const currentPage = ref(props.movies.current_page);

const posterUrl = useFirstImage(props.person, 'profile');

const locale = useI18n().locale.value;

const name = useName(props.person, locale);

const goToPage = (page) => {
    const pageLink = props.movies.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        router.visit(pageLink.url, {
            preserveScroll: true,
            preserveState: true,
            only: ['movies'],
        });
    }
};
</script>

<template>
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
                        {{ name }}
                        <q-badge
                            class="text-subtitle1"
                            align="middle"
                            color="grey-7"
                        >
                            {{
                                $t('web.personShow.moviesCount', {
                                    number: movieCount.toLocaleString(),
                                })
                            }}
                        </q-badge>
                    </h1>
                    <span
                        v-if="name !== person.name['ja-JP']"
                        class="text-subtitle1"
                    >
                        {{ person.name['ja-JP'] }}
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
                                person.birthdate ?? $t('web.general.unknown')
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
                            <span v-else>{{ $t('web.general.unknown') }}</span>
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
                                person.blood_type ?? $t('web.general.unknown')
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
                                    ? $t('web.model.unit_cm', {
                                          number: person.height.toLocaleString(),
                                      })
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
                                    ? $t('web.model.unit_cm', {
                                          number: person.bust.toLocaleString(),
                                      })
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
                                    ? $t('web.model.unit_cm', {
                                          number: person.waist.toLocaleString(),
                                      })
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
                                    ? $t('web.model.unit_cm', {
                                          number: person.hip.toLocaleString(),
                                      })
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
                                    person.cup_size ?? $t('web.general.unknown')
                                }}
                                <template
                                    v-if="person.breast_implants !== null"
                                >
                                    {{
                                        person.breast_implants
                                            ? $t('web.model.breasts_implant')
                                            : $t('web.model.breasts_natural')
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
        <div class="row justify-center q-mb-md">
            <q-pagination
                v-model="currentPage"
                :max="props.movies.last_page"
                :max-pages="6"
                boundary-numbers
                @update:model-value="goToPage"
            />
        </div>
        <div
            class="fit row wrap justify-start items-start content-start q-gutter-md"
        >
            <MovieCard
                v-for="movie in movies.data"
                :key="movie.id"
                :movie="movie"
            />
        </div>
        <div class="row justify-center q-mb-md">
            <q-pagination
                v-model="currentPage"
                :max="props.movies.last_page"
                :max-pages="6"
                boundary-numbers
                @update:model-value="goToPage"
            />
        </div>
    </div>
</template>
