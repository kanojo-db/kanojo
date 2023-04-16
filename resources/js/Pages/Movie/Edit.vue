<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { useI18n } from 'vue-i18n';

import MovieTabBar from '@/Components/MovieTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Movie } from '@/types/models';
import { useFirstImage, useTitle } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const locale = useI18n().locale;

const title = useTitle(props.movie, locale.value);

const posterUrl = useFirstImage(props.movie);

const movieEditForm = useForm({
    title: props.movie.title[locale.value],
    original_title: props.movie.title['ja-JP'],
    product_code: props.movie.product_code,
    release_date: props.movie.release_date,
    runtime: props.movie.length,
    type: props.movie.type,
});

const submit = () => {
    movieEditForm
        .transform((data) => ({
            ...data,
        }))
        .patch($route('movies.update', props.movie.id));
};
</script>

<template>
    <div class="col bg-grey-3">
        <MovieTabBar :movie="movie" />
        <div class="row q-py-md q-px-md">
            <div
                class="q-pl-none q-mr-lg"
                style="max-width: 300px"
            >
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="80px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center"
                    style="width: 80px; height: 120px"
                >
                    <q-icon
                        name="mdi-help"
                        size="60px"
                        color="grey-2"
                    />
                </div>
            </div>
            <div class="col flex">
                <div
                    class="column full-height justify-start items-start q-mb-sm"
                >
                    <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                        {{ title }}
                    </h1>
                    <Link
                        :href="$route('movies.show', movie)"
                        class="text-subtitle1"
                    >
                        <q-icon
                            name="mdi-arrow-left"
                            size="14px"
                        />
                        Back to Overview
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <div class="q-ma-md">
        <div class="row q-col-gutter-md full-width">
            <div class="col-2 q-pl-none">
                <q-card
                    class="my-card"
                    flat
                    bordered
                >
                    <q-card-section
                        class="bg-primary text-white row items-center"
                    >
                        <div class="text-weight-bold text-h6">Edit</div>
                    </q-card-section>

                    <q-separator />

                    <q-list
                        dense
                        bordered
                        padding
                    >
                        <q-item clickable>
                            <q-item-section>General</q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-10">
                <q-form @submit.prevent="submit">
                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="title"
                                v-model="movieEditForm.title"
                                label="Title"
                                filled
                                :error="!!movieEditForm?.errors?.title"
                                :error-message="movieEditForm.errors.title"
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="original_title"
                                v-model="movieEditForm.original_title"
                                label="Original Title"
                                filled
                                required
                                :error="!!movieEditForm?.errors?.original_title"
                                :error-message="
                                    movieEditForm.errors.original_title
                                "
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-btn
                                color="primary"
                                label="Edit"
                                type="submit"
                                :disabled="movieEditForm.processing"
                            />
                        </div>
                    </div>
                </q-form>
            </div>
        </div>
    </div>
</template>
