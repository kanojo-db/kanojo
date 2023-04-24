<script setup lang="ts">
import type { MovieTypes, Series, Studio } from '@/types/models';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import useRouteStore from '@/stores/route';
import { getName, getTitle } from '@/utils/item';

const page = usePage();

const studios = computed(() => {
    return page.props.studios as Studio[];
});

const series = computed(() => {
    return page.props.series as Series[];
});

const movieTypes = computed(() => {
    return page.props.movieTypes as MovieTypes[];
});

const locale = useI18n().locale;

const routeStore = useRouteStore();

const currentRoute = computed(() => {
    return routeStore.current;
});

const routeParams = computed(() => {
    return routeStore.params;
});

const studioSearch = ref('');
const studioSearchLoading = ref(false);

watch(studioSearch, (value) => {
    studioSearchLoading.value = true;

    router.get(
        route(currentRoute.value, {
            ...routeParams.value,
            studio: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['studios'],
            onSuccess: () => {
                studioSearchLoading.value = false;
            },
        },
    );
});

const seriesSearch = ref('');
const seriesSearchLoading = ref(false);

watch(seriesSearch, (value) => {
    seriesSearchLoading.value = true;

    router.get(
        route(currentRoute.value, {
            ...routeParams.value,
            series: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['series'],
            onSuccess: () => {
                seriesSearchLoading.value = false;
            },
        },
    );
});

const form = useForm({
    title: '',
    originalTitle: '',
    productCode: '',
    releaseDate: null,
    runtime: 0,
    studioId: null,
    movieTypeId: null,
    seriesId: null,
});

const submit = () => {
    form.post(route('movies.store'));
};
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="type"
                        text="Type *"
                    />
                </div>

                <v-select
                    v-model="form.movieTypeId"
                    name="type"
                    :items="movieTypes"
                    item-value="id"
                    item-title="name"
                    :error-messages="form.errors.movieTypeId"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="release_date"
                        text="Release Date"
                    />
                </div>

                <v-text-field
                    v-model="form.releaseDate"
                    type="date"
                    name="release_date"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.releaseDate"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="original_title"
                        text="Original Title *"
                    />
                </div>

                <v-text-field
                    v-model="form.originalTitle"
                    name="original_title"
                    required
                    :error-messages="form.errors.originalTitle"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="translated_title"
                        :text="`Translated Title (${locale})`"
                    />
                </div>

                <v-text-field
                    v-model="form.title"
                    name="translated_title"
                    :error-messages="form.errors.title"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="studio"
                        text="Studio"
                    />
                </div>

                <v-autocomplete
                    v-model="form.studioId"
                    v-model:search="studioSearch"
                    :loading="studioSearchLoading"
                    :items="studios"
                    name="studio"
                    item-value="id"
                    :item-title="(item: Studio) => getName(item, locale)"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.studioId"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="series"
                        text="Series"
                    />
                </div>

                <v-autocomplete
                    v-model="form.seriesId"
                    v-model:search="seriesSearch"
                    :loading="seriesSearchLoading"
                    :items="series"
                    name="series"
                    item-value="id"
                    :item-title="(item: Series) => getTitle(item, locale)"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.studioId"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="runtime"
                        text="Runtime"
                    />
                </div>

                <v-text-field
                    v-model="form.runtime"
                    name="runtime"
                    stack-label
                    required
                    :error="!!form?.errors?.runtime"
                    :error-message="form.errors.runtime"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-btn
                    color="primary"
                    text="Add"
                    type="submit"
                    :loading="form.processing"
                />
            </v-col>
        </v-row>
    </v-form>
</template>
