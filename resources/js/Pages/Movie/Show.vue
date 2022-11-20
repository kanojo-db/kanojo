<script setup>
import { computed } from "vue";
import { Link, useForm, usePage } from "@inertiajs/inertia-vue3";
import { DateTime, Duration } from "luxon";

import AppLayout from "@/Layouts/AppLayout.vue";
import MovieTabBar from "@/Components/MovieTabBar.vue";

const { movie } = defineProps({
    movie: Object,
    reactions: Object,
});

const posterUrl = computed(() => {
    if (movie?.media && movie.media.length > 0) {
        return movie.media.filter((m) => m.collection_name === "poster")?.[0]
            .original_url;
    }

    return null;
});

const getHumanReadableDate = (date) => {
    return DateTime.fromSQL(date).toLocaleString(DateTime.DATE_SHORT);
};

const getModelAge = (date) => {
    if (DateTime.fromSQL(movie.release_date)) {
        const modelDate = DateTime.fromSQL(date)
            .diff(DateTime.fromISO(movie.release_date), "years")
            .toObject();

        return Math.floor(Math.abs(modelDate.years));
    }

    return null;
};

const getModelImage = (model) => {
    if (model?.media && model.media.length > 0) {
        return model.media.filter((m) => m.collection_name === "profile")?.[0]
            .original_url;
    }

    return null;
};

const movieReleaseDate = computed(() => {
    return DateTime.fromSQL(movie.release_date).toLocaleString(
        DateTime.DATE_SHORT
    );
});

const movieDuration = computed(() => {
    return Duration.fromObject({ minutes: movie.length });
});

const hasLiked = computed(() => {
    const userLike = movie.love_reactant.reactions.filter((reaction) => {
        return (
            reaction.reacter.reacterable.id === usePage().props.value.user.id
        );
    });

    return userLike.length > 0 && userLike[0].reaction_type_id === 1;
});

const hasDisliked = computed(() => {
    const userDislike = movie.love_reactant.reactions.filter((reaction) => {
        return (
            reaction.reacter.reacterable.id === usePage().props.value.user.id
        );
    });

    return userDislike.length > 0 && userDislike[0].reaction_type_id === 2;
});

const title = computed(() =>
    movie.title.en ? movie.title.en : movie.title.jp
);

const likeForm = useForm({});

const likeMovie = () => {
    likeForm.post(route("movies.like.store", [movie]));
};

const dislikeMovie = () => {
    likeForm.dislike = true;

    likeForm.post(route("movies.dislike.store", [movie]), {
        onSuccess: () => (likeForm.dislike = false),
    });
};
</script>

<template>
    <AppLayout :title="title">
        <div class="col bg-grey-3">
            <MovieTabBar :movie="movie" />
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
                    <q-icon name="mdi-help" size="150px" color="grey-2" />
                </div>
                <div class="col">
                    <div class="row q-mb-sm">
                        <h1 class="text-h4 q-mt-none q-mb-sm ellipsis-2-lines">
                            {{ title }}
                        </h1>
                        <div
                            class="fit row wrap justify-start items-center content-start"
                        >
                            <q-chip
                                class="q-ml-none"
                                outline
                                square
                                color="secondary"
                                >{{ movie.type.name }}</q-chip
                            >
                            <span v-if="movie.release_date" class="text-body1 q-mx-md q-mt-none">{{
                                movieReleaseDate
                            }}</span>
                            <span
                                v-if="movie.length > 0"
                                class="text-body1 q-mt-none"
                                >{{
                                    movieDuration.toHuman({
                                        unitDisplay: "short",
                                    })
                                }}</span
                            >
                        </div>
                    </div>
                    <div class="row justify-start items-center">
                        <div class="row justify-start items-center">
                            <q-knob
                                readonly
                                :min="0"
                                :max="10"
                                v-model="movie.average_rating"
                                show-value
                                size="65px"
                                :thickness="0.15"
                                color="primary"
                                center-color="grey-5"
                                track-color="grey-5"
                            >
                                <div
                                    class="row justify-center items-start text-overline"
                                >
                                    <span class="text-weight-bolder text-h6">{{
                                        movie.average_rating * 10
                                    }}</span
                                    >%
                                </div>
                            </q-knob>
                            <div class="q-ml-sm text-weight-bold q-mr-md">
                                User<br />
                                Score
                            </div>
                        </div>
                        <q-btn
                            unelevated
                            round
                            :color="hasLiked ? 'secondary' : 'primary'"
                            icon="mdi-thumb-up"
                            @click="likeMovie"
                        >
                            <q-tooltip class="bg-primary"> Like </q-tooltip>
                        </q-btn>
                        <q-btn
                            unelevated
                            round
                            :color="hasDisliked ? 'secondary' : 'primary'"
                            icon="mdi-thumb-down"
                            class="q-mx-sm"
                            @click="dislikeMovie"
                        >
                            <q-tooltip class="bg-primary"> Dislike </q-tooltip>
                        </q-btn>
                    </div>
                    <div class="row q-mt-sm">
                        <q-chip
                            v-for="tag in movie.tags"
                            :key="`movie-tag-${tag.id}`"
                            color="grey-4"
                            >{{ tag.name.en ? tag.name.en : tag.name.jp }}</q-chip
                        >
                    </div>
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <div class="row q-col-gutter-lg full-width">
                <div class="col wrap q-pl-none">
                    <div
                        class="fit row wrap justify-start items-start content-start q-col-gutter-md"
                    >
                        <div
                            v-for="model in movie.models"
                            :key="`movie-model-${model.id}`"
                            class="col-4"
                        >
                            <Link :href="route('models.show', model)">
                                <div
                                    class="row bg-pink-1 q-pa-md rounded-borders"
                                >
                                    <div class="col-2 q-pr-none q-mr-lg">
                                        <q-avatar
                                            size="90px"
                                            color="white"
                                        >
                                            <q-img
                                                v-if="getModelImage(model)"
                                                :src="getModelImage(model)"
                                                :ratio="1"
                                                fit="cover"
                                            />
                                            <q-icon
                                                v-else
                                                name="mdi-help"
                                                size="52px"
                                                color="pink-1"
                                            />
                                        </q-avatar>
                                    </div>
                                    <div class="col">
                                        <div class="text-h6 q-my-none">
                                            {{
                                                model.name.en
                                                    ? model.name.en
                                                    : model.name.jp
                                            }}
                                        </div>
                                        <div
                                            v-if="model.birthdate"
                                            class="text-body1 q-mt-none"
                                        >
                                            Born:
                                            {{
                                                getHumanReadableDate(
                                                    model.birthdate
                                                )
                                            }}
                                            <span
                                                v-if="
                                                    getModelAge(model.birthdate)
                                                "
                                                >({{
                                                    getModelAge(model.birthdate)
                                                }}
                                                years old)</span
                                            >
                                        </div>
                                        <div
                                            v-else
                                            class="text-body1 q-mt-none"
                                        >
                                            Born: Unknown
                                        </div>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="col-2">
                    <p>
                        <strong class="block">Product Code</strong>
                        {{ movie.product_code }}
                    </p>
                    <p v-if="movie.title.en">
                        <strong class="block">Original Title</strong>
                        {{ movie.title.jp }}
                    </p>
                    <p v-if="movie.studio">
                        <strong class="block">Studio</strong>
                        {{
                            movie.studio.name.en
                                ? movie.studio.name.en
                                : movie.studio.name.jp
                        }}
                    </p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
