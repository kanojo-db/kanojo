<script setup>
import { computed } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import MovieTabBar from "@/Components/MovieTabBar.vue";

const { movie } = defineProps({
    movie: Object,
});

const title = computed(() =>
    movie.title.en ? movie.title.en : movie.title.jp
);

const posterUrl = computed(() => {
    if (movie?.media && movie.media.length > 0) {
        return movie.media.filter((m) => m.collection_name === "poster")?.[0]
            .original_url;
    }

    return null;
});

const movieEditForm = useForm({
    title: movie.title.en,
    original_title: movie.title.jp,
    product_code: movie.product_code,
    release_date: movie.release_date,
    runtime: movie.runtime,
    type: movie.type,
});

const submit = () => {
    movieEditForm.transform((data) => ({
        ...data,
    })).patch(route("movies.update", movie.id));
};
</script>

<template>
    <AppLayout :title="`${title} - Edit`">
        <div class="col bg-grey-3">
            <MovieTabBar :movie="movie" />
            <div class="row q-py-md q-px-md">
                <div class="q-pl-none q-mr-lg" style="max-width: 300px">
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
                        <q-icon name="mdi-help" size="60px" color="grey-2" />
                    </div>
                </div>
                <div class="col flex">
                    <div
                        class="column full-height justify-start items-start q-mb-sm"
                    >
                        <h1
                            class="text-h4 q-mt-none q-mb-none ellipsis-2-lines"
                        >
                            {{ title }}
                        </h1>
                        <Link
                            :href="route('movies.show', movie)"
                            class="text-subtitle1"
                        >
                            <q-icon name="mdi-arrow-left" size="14px" />
                            Back to Overview
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="q-ma-md">
            <div class="row q-col-gutter-md full-width">
                <div class="col-2 q-pl-none">
                    <q-card class="my-card" flat bordered>
                        <q-card-section
                            class="bg-primary text-white row items-center"
                        >
                            <div class="text-weight-bold text-h6">Edit</div>
                        </q-card-section>

                        <q-separator />

                        <q-list dense bordered padding>
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
                                    autofocus
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
                                    :error="
                                        !!movieEditForm?.errors?.original_title
                                    "
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
    </AppLayout>
</template>
