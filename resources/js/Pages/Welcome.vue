<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import CardSwiper from "@/Components/CardSwiper.vue";
import ModelCardSwiper from "@/Components/ModelCardSwiper.vue";

defineProps({
    popularModels: Object,
    popularMovies: Object,
    latestMovies: Object,
    recentlyUpdatedMovies: Object,
    recentlyReleasedMovies: Object,
    movieCount: Number,
    modelCount: Number,
    tagCount: Number,
});

const searchForm = useForm({
    q: "",
});

const submit = () => {
    searchForm.get(route("search"));
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
                Find your next crush
            </h1>
            <q-form @submit.prevent="submit">
                <q-input
                    rounded
                    outlined
                    v-model="searchForm.q"
                    placeholder="Search a title, product code or model..."
                    bgColor="grey-1"
                    class="q-mb-xl"
                    style="width: 650px"
                >
                    <template v-slot:append>
                        <q-btn
                            type="submit"
                            rounded
                            color="primary"
                            @click="submit"
                        >
                            Search
                        </q-btn>
                    </template>
                </q-input></q-form
            >
            <div class="row full-width">
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                        >Titles</span
                    >
                    <span class="text-weight-bolder text-h4">{{
                        movieCount
                    }}</span>
                </div>
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                        >Models</span
                    >
                    <span class="text-weight-bolder text-h4">{{
                        modelCount
                    }}</span>
                </div>
                <div
                    class="col fit column justify-center items-center content-center text-white q-mx-md"
                >
                    <span
                        class="text-weight-bold text-uppercase text-h6 text-center"
                        >Categories</span
                    >
                    <span class="text-weight-bolder text-h4">{{
                        tagCount
                    }}</span>
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <section>
                <h1 class="text-h6 q-mt-sm">Recently Released Movies</h1>
                <CardSwiper :movies="recentlyReleasedMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">Popular Models</h1>
                <ModelCardSwiper :models="popularModels" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">Popular Movies</h1>
                <CardSwiper :movies="popularMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">Recently Added Movies</h1>
                <CardSwiper :movies="latestMovies" />
            </section>
            <section>
                <h1 class="text-h6 q-mt-sm">Recently Updated Movies</h1>
                <CardSwiper :movies="recentlyUpdatedMovies" />
            </section>
        </div>
    </AppLayout>
</template>
