<script setup lang="ts">
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { DateTime, Duration } from 'luxon';
import { PropType, computed } from 'vue';
import { useI18n } from 'vue-i18n';

import ModelRoleCard from '@/Components/ModelRoleCard.vue';
import MovieTabBar from '@/Components/MovieTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { PageProps } from '@/types/inertia';
import { Movie } from '@/types/models';
import { useFirstImage, useName, useTitle } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const posterUrl = useFirstImage(props.movie, 'front_cover');

const isVrMovie = computed(() => {
    return props.movie.type.name === 'VR' || props.movie.type_id === 4;
});

const movieReleaseDate = computed(() => {
    if (!props.movie.release_date) {
        return null;
    }

    return DateTime.fromISO(props.movie.release_date).toLocaleString(
        DateTime.DATE_SHORT,
    );
});

const movieDuration = computed(() => {
    if (!props.movie.length) {
        return null;
    }

    return Duration.fromObject({ minutes: props.movie.length });
});

const page = usePage<PageProps>();

const hasLiked = computed(() => {
    const userLike = props.movie?.love_reactant?.reactions.filter(
        (reaction) => {
            return reaction.reacter.reacterable.id === page?.props?.user?.id;
        },
    );

    return userLike?.length > 0 && userLike?.[0]?.reaction_type_id === 1;
});

const hasDisliked = computed(() => {
    const userDislike = props.movie?.love_reactant?.reactions.filter(
        (reaction) => {
            return reaction.reacter.reacterable.id === page?.props?.user?.id;
        },
    );

    return userDislike?.length > 0 && userDislike?.[0]?.reaction_type_id === 2;
});

const { t, locale } = useI18n();

const title = useTitle(props.movie, locale.value);
const studioName = useName(props.movie.studio, locale.value);

const averageScore = computed(() => {
    if (props.movie?.love_reactant?.reaction_total?.count) {
        const likeReactions = props.movie?.love_reactant?.reactions.filter(
            (reaction) => reaction.type.name === 'Like',
        );

        return (
            (likeReactions.length /
                props.movie?.love_reactant?.reaction_total.count) *
            100
        );
    }

    return 0;
});

const userScore = computed(() => {
    if (props.movie?.love_reactant?.reaction_total?.count) {
        return `${averageScore.value}`;
    }

    return t('web.general.not_rated');
});

const likeForm = useForm({});

const likeMovie = () => {
    likeForm.post(route('movies.like.store', [props.movie]), {
        onSuccess: () => likeForm.reset(),
    });
};

const dislikeMovie = () => {
    likeForm.post(route('movies.dislike.store', [props.movie]), {
        onSuccess: () => likeForm.reset(),
    });
};

const groupedTags = computed(() => {
    const tags = props.movie.tags;

    const groupedTags = tags.reduce((acc, tag) => {
        if (!acc[tag.type]) {
            acc[tag.type] = [];
        }

        acc[tag.type].push(tag);

        return acc;
    }, {});

    return groupedTags;
});
</script>

<template>
    <div
        itemscope
        itemtype="https://schema.org/Movie"
        :title="title"
    >
        <div class="col bg-grey-3">
            <MovieTabBar :movie="movie" />
            <div class="row q-py-lg q-px-md">
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    itemprop="image"
                    :content="posterUrl"
                    height="450px"
                    width="300px"
                    :fit="isVrMovie ? 'contain' : 'cover'"
                    class="bg-grey-2 rounded-borders q-mr-lg"
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
                    <div class="row q-mb-sm">
                        <h1
                            itemprop="name"
                            class="text-h4 q-mt-none q-mb-sm ellipsis-2-lines"
                        >
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
                            >
                                {{ movie.type.name }}
                            </q-chip>
                            <span
                                v-if="movie.release_date"
                                itemprop="datePublished"
                                :content="movie.release_date"
                                class="text-body1 q-mx-md q-mt-none"
                            >
                                {{ movieReleaseDate }}
                            </span>
                            <span
                                v-if="movie.length"
                                itemprop="duration"
                                class="text-body1 q-mt-none"
                            >
                                {{
                                    movieDuration?.toHuman({
                                        unitDisplay: 'short',
                                    })
                                }}
                            </span>
                        </div>
                    </div>
                    <div class="row justify-start items-center">
                        <div
                            itemprop="aggregateRating"
                            itemscope
                            itemtype="https://schema.org/AggregateRating"
                            class="row justify-start items-center"
                        >
                            <meta
                                itemprop="ratingCount"
                                :content="
                                    props.movie?.love_reactant?.reaction_total
                                        ?.count ?? 0
                                "
                            />
                            <meta
                                itemprop="bestRating"
                                content="100"
                            />
                            <meta
                                itemprop="worstRating"
                                content="0"
                            />
                            <q-knob
                                :value="averageScore"
                                readonly
                                :min="0"
                                :max="10"
                                show-value
                                size="65px"
                                :thickness="0.15"
                                color="primary"
                                center-color="grey-5"
                                track-color="grey-5"
                                itemprop="ratingValue"
                                :content="averageScore"
                                :model-value="averageScore"
                            >
                                <div
                                    class="row justify-center items-start text-overline"
                                >
                                    <i18n-t
                                        v-if="averageScore > 0"
                                        keypath="web.general.x_percent"
                                    >
                                        <template #number>
                                            <span
                                                class="text-weight-bolder text-h6"
                                            >
                                                {{ userScore }}
                                            </span>
                                        </template>
                                    </i18n-t>
                                    <span
                                        v-else
                                        class="text-weight-bolder text-h6"
                                    >
                                        {{ $t('web.general.not_rated') }}
                                    </span>
                                </div>
                            </q-knob>
                            <div class="q-ml-sm text-weight-bold q-mr-md">
                                {{ $t('web.movie.show.score') }}
                            </div>
                        </div>
                        <q-btn
                            unelevated
                            round
                            :color="hasLiked ? 'secondary' : 'primary'"
                            icon="mdi-thumb-up"
                            @click="likeMovie"
                        >
                            <q-tooltip class="bg-primary">
                                {{
                                    hasLiked
                                        ? $t('web.movie.show.remove_like')
                                        : $t('web.movie.show.like')
                                }}
                            </q-tooltip>
                        </q-btn>
                        <q-btn
                            unelevated
                            round
                            :color="hasDisliked ? 'secondary' : 'primary'"
                            icon="mdi-thumb-down"
                            class="q-ml-sm"
                            @click="dislikeMovie"
                        >
                            <q-tooltip class="bg-primary">
                                {{
                                    hasDisliked
                                        ? $t('web.movie.show.remove_dislike')
                                        : $t('web.movie.show.dislike')
                                }}
                            </q-tooltip>
                        </q-btn>
                    </div>
                    <div class="row justify-start items-center q-mt-md">
                        <q-btn
                            unelevated
                            :color="
                                props.movie.is_collection
                                    ? 'secondary'
                                    : 'primary'
                            "
                            icon="mdi-plus"
                            :label="
                                props.movie.is_collection
                                    ? $t(
                                          'web.movie.show.remove_from_collection',
                                      )
                                    : $t('web.movie.show.add_to_collection')
                            "
                            @click="
                                props.movie.is_collection
                                    ? $inertia.delete(
                                          route('movies.collection.destroy', [
                                              movie,
                                          ]),
                                      )
                                    : $inertia.post(
                                          route('movies.collection.store', [
                                              movie,
                                          ]),
                                      )
                            "
                        />
                        <q-btn
                            unelevated
                            :color="
                                props.movie.is_favorite
                                    ? 'secondary'
                                    : 'primary'
                            "
                            icon="mdi-heart"
                            :label="
                                props.movie.is_favorite
                                    ? $t('web.movie.show.remove_from_favorites')
                                    : $t('web.movie.show.add_to_favorites')
                            "
                            class="q-ml-sm"
                            @click="
                                props.movie.is_favorite
                                    ? $inertia.delete(
                                          route('movies.favorite.destroy', [
                                              movie,
                                          ]),
                                      )
                                    : $inertia.post(
                                          route('movies.favorite.store', [
                                              movie,
                                          ]),
                                      )
                            "
                        />
                        <q-btn
                            unelevated
                            :color="
                                props.movie.is_wishlist
                                    ? 'secondary'
                                    : 'primary'
                            "
                            icon="mdi-bookmark"
                            :label="
                                props.movie.is_wishlist
                                    ? $t('web.movie.show.remove_from_wishlist')
                                    : $t('web.movie.show.add_to_wishlist')
                            "
                            class="q-ml-sm"
                            @click="
                                props.movie.is_wishlist
                                    ? $inertia.delete(
                                          route('movies.wishlist.destroy', [
                                              movie,
                                          ]),
                                      )
                                    : $inertia.post(
                                          route('movies.wishlist.store', [
                                              movie,
                                          ]),
                                      )
                            "
                        />
                    </div>
                    <div class="row q-mt-sm">
                        <table>
                            <tr
                                v-for="(
                                    tagGroupItems, tagGroupName, tagGroupIndex
                                ) in groupedTags"
                                :key="`movie-tag-group-${tagGroupIndex}`"
                            >
                                <td class="text-weight-bold q-pr-md">
                                    {{
                                        tagGroupName === 'null'
                                            ? $t(
                                                  'web.movie.show.unknown_birth_date',
                                              )
                                            : tagGroupName
                                    }}
                                </td>
                                <td>
                                    <q-chip
                                        v-for="tag in tagGroupItems"
                                        :key="`movie-tag-${tag.id}`"
                                        color="grey-4"
                                    >
                                        {{ tag }}
                                    </q-chip>
                                </td>
                            </tr>
                        </table>
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
                        <ModelRoleCard
                            v-for="model in movie.models"
                            :key="`movie-model-${model.id}`"
                            :movie="movie"
                            :model="model"
                        />
                    </div>
                </div>
                <div class="col-2">
                    <p>
                        <strong class="block">
                            {{ $t('web.movie.show.product_code') }}
                        </strong>
                        {{ movie.product_code }}
                    </p>
                    <p v-if="movie.title[locale]">
                        <strong class="block">
                            {{ $t('web.movie.show.original_title') }}
                        </strong>
                        {{ movie.title['ja-JP'] }}
                    </p>
                    <p
                        v-if="movie.studio"
                        itemprop="productionCompany"
                        itemscope
                        itemtype="https://schema.org/Organization"
                    >
                        <strong class="block">
                            {{ $t('web.movie.show.studio') }}
                        </strong>
                        <Link
                            :href="route('studios.show', movie.studio)"
                            itemprop="name"
                        >
                            {{ studioName }}
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
