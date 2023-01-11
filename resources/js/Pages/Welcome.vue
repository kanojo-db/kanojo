<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import { computed } from 'vue';

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
    topUsers: {
        type: Object,
        required: true,
    },
});

const searchForm = useForm({
    q: '',
});

const submit = () => {
    searchForm.get(route('search'));
};

const maxTotalAudits = computed(() => {
    let max = 0;

    props.topUsers.forEach((user) => {
        if (user.total_audits > max) {
            max = user.total_audits;
        }
    });

    return max;
});

const maxAuditsThisWeek = computed(() => {
    let max = 0;

    props.topUsers.forEach((user) => {
        if (user.audits_this_week > max) {
            max = user.audits_this_week;
        }
    });

    return max;
});

const orderedTopUsers = computed(() => {
    const topUsers = props.topUsers;

    return topUsers.sort((a, b) => {
        if (a.audits_this_week > b.audits_this_week) {
            return -1;
        }

        if (a.audits_this_week < b.audits_this_week) {
            return 1;
        }

        return 0;
    });
});
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
                <h1 class="text-h4 q-mt-sm q-mb-lg text-weight-bold">
                    {{ $t('web.welcome.recentlyReleasedMovies') }}
                </h1>
                <CardSwiper :movies="props.recentlyReleasedMovies" />
            </section>
            <section>
                <h1 class="text-h4 q-mt-sm q-mb-lg text-weight-bold">
                    {{ $t('web.welcome.popularModels') }}
                </h1>
                <ModelCardSwiper :models="props.popularModels" />
            </section>
            <section>
                <h1 class="text-h4 q-mt-sm q-mb-lg text-weight-bold">
                    {{ $t('web.welcome.popularMovies') }}
                </h1>
                <CardSwiper :movies="props.popularMovies" />
            </section>
            <section>
                <h1 class="text-h4 q-mt-sm q-mb-lg text-weight-bold">
                    {{ $t('web.welcome.recentlyAddedMovies') }}
                </h1>
                <CardSwiper :movies="props.latestMovies" />
            </section>
            <section>
                <h1 class="text-h4 q-mt-sm q-mb-lg text-weight-bold">
                    {{ $t('web.welcome.recentlyUpdatedMovies') }}
                </h1>
                <CardSwiper :movies="props.recentlyUpdatedMovies" />
            </section>
            <section>
                <div class="row q-mt-sm q-mb-xs">
                    <h1 class="text-h4 text-weight-bold">
                        {{ $t('web.welcome.leaderboard') }}
                    </h1>
                    <div class="column justify-center items-start q-ml-lg">
                        <div class="row justify-start items-center">
                            <div
                                class="bg-primary rounded-borders"
                                style="width: 10px; height: 10px"
                            />
                            <div
                                class="col-auto q-ml-md text-caption text-weight-bolder"
                            >
                                {{ $t('web.welcome.leaderboardTotalEdits') }}
                            </div>
                        </div>
                        <div class="row justify-start items-center">
                            <div
                                class="bg-secondary rounded-borders"
                                style="width: 10px; height: 10px"
                            />
                            <div
                                class="col-auto q-ml-md text-caption text-weight-bolder"
                            >
                                {{ $t('web.welcome.leaderboardEditsThisWeek') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="fit grid-2 q-mb-xl">
                    <div
                        v-for="user in orderedTopUsers"
                        :key="`top-user-${user.id}`"
                        class="row items-center"
                    >
                        <q-avatar
                            size="90px"
                            class="bg-grey-1"
                        >
                            <q-icon
                                name="mdi-account"
                                size="48px"
                                color="grey-2"
                            />
                        </q-avatar>
                        <div class="col-grow column q-ml-md">
                            <h3
                                class="text-h5 text-weight-bold q-mt-none q-mb-xs"
                            >
                                {{ user.name }}
                            </h3>
                            <div class="row no-wrap items-center">
                                <div
                                    class="bg-primary rounded-borders"
                                    :style="`width: ${
                                        maxAudits === 0
                                            ? 0
                                            : (user.total_audits /
                                                  maxTotalAudits) *
                                              100
                                    }%; height: 10px`"
                                />
                                <div
                                    class="col-auto q-ml-md text-subtitle1 text-weight-bolder"
                                >
                                    {{ user.total_audits.toLocaleString() }}
                                </div>
                            </div>
                            <div class="row no-wrap items-center">
                                <div
                                    class="bg-secondary rounded-borders"
                                    :style="`width: ${
                                        maxAuditsThisWeek === 0
                                            ? 0
                                            : (user.audits_this_week /
                                                  maxAuditsThisWeek) *
                                              100
                                    }%; height: 10px`"
                                />
                                <div
                                    class="col-auto q-ml-md text-subtitle1 text-weight-bolder"
                                >
                                    {{ user.audits_this_week.toLocaleString() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AppLayout>
</template>
