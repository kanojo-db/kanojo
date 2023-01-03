<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import CardSwiper from '@/Components/CardSwiper.vue';
import ModelCardSwiper from '@/Components/ModelCardSwiper.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    popularModels: {
        type: Object,
        required: true,
    },
    popularMovies: {
        type: Object,
        required: true,
    },
    latestMovies: {
        type: Object,
        required: true,
    },
    recentlyUpdatedMovies: {
        type: Object,
        required: true,
    },
    recentlyReleasedMovies: {
        type: Object,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
    modelCount: {
        type: Number,
        required: true,
    },
    tagCount: {
        type: Number,
        required: true,
    },
});

const searchForm = useForm({
    q: '',
});

const submit = () => {
    searchForm.get(route('search'));
};
</script>

<template>
    <AppLayout>
        <div
            class="bg-grey-8 full-width column justify-center items-center q-py-xl"
        >
            <h1
                class="text-white text-h3 text-center text-weight-bolder text-uppercase q-mt-none q-mb-xl"
            >
                {{ $t('web.welcome.slogan') }}
            </h1>
            <q-form @submit.prevent="submit">
                <q-input
                    v-model="searchForm.q"
                    rounded
                    outlined
                    placeholder="Search a title, product code or model..."
                    bg-color="grey-1"
                    class="q-mb-xl"
                    style="width: 650px"
                >
                    <template #append>
                        <q-btn
                            type="submit"
                            rounded
                            color="primary"
                            @click="submit"
                        >
                            {{ $t('web.general.search') }}
                        </q-btn>
                    </template>
                </q-input>
            </q-form>
            <div class="row full-width">
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                    >
                        {{ $t('web.welcome.countMovies') }}
                    </span>
                    <span class="text-weight-bolder text-h4">
                        {{ props.movieCount.toLocaleString() }}
                    </span>
                </div>
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                    >
                        {{ $t('web.welcome.countModels') }}
                    </span>
                    <span class="text-weight-bolder text-h4">
                        {{ props.modelCount.toLocaleString() }}
                    </span>
                </div>
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                    >
                        {{ $t('web.welcome.countCategories') }}
                    </span>
                    <span class="text-weight-bolder text-h4">
                        {{ props.tagCount.toLocaleString() }}
                    </span>
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <section>
                <h1 class="text-h6 q-mt-sm">
                    {{ $t('web.welcome.recentlyReleasedMovies') }}
                </h1>
                <CardSwiper :movies="props.recentlyReleasedMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">
                    {{ $t('web.welcome.popularModels') }}
                </h1>
                <ModelCardSwiper :models="props.popularModels" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">
                    {{ $t('web.welcome.popularMovies') }}
                </h1>
                <CardSwiper :movies="props.popularMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">
                    {{ $t('web.welcome.recentlyAddedMovies') }}
                </h1>
                <CardSwiper :movies="props.latestMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">
                    {{ $t('web.welcome.recentlyUpdatedMovies') }}
                </h1>
                <CardSwiper :movies="props.recentlyUpdatedMovies" />
            </section>
        </div>
    </AppLayout>
</template>
