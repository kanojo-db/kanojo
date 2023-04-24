<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { router, usePage } from '@inertiajs/vue3';
import { Duration } from 'luxon';
import { computed, inject, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiThumbDown from '~icons/mdi/thumb-down';
import MdiThumbUp from '~icons/mdi/thumb-up';

import UserRating from '@/Components/UserRating.vue';
import { getItemRouteParams, useTitle } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const page = usePage();

const locale = useI18n().locale;

const title = useTitle(props.item, locale.value);

const isTextWhite = inject<boolean>('isTextWhite');

const movieDuration = computed(() => {
    if (!props.item.length) {
        return null;
    }

    return Duration.fromObject({ minutes: props.item.length });
});

const hasLiked = computed(() => {
    const userLike = props.item?.love_reactant?.reactions.filter((reaction) => {
        return reaction.reacter.reacterable.id === page?.props?.user?.id;
    });

    return userLike.length > 0 && userLike?.[0]?.reaction_type_id === 1;
});

const hasDisliked = computed(() => {
    const userDislike = props.item?.love_reactant?.reactions.filter(
        (reaction) => {
            return reaction.reacter.reacterable.id === page?.props?.user?.id;
        },
    );

    return userDislike.length > 0 && userDislike?.[0]?.reaction_type_id === 2;
});

const itemLike = computed({
    get() {
        if (hasLiked.value) {
            return 0;
        } else if (hasDisliked.value) {
            return 1;
        }

        return null;
    },
    set(value: number | null) {
        if (value === 0) {
            likeMovie();
        } else if (value === 1) {
            dislikeMovie();
        } else {
            return;
        }
    },
});

const likeMovie = () => {
    router.post(
        route('movies.like.store', getItemRouteParams(props.item)),
        {},
        {
            onSuccess: () => {
                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
        },
    );
};

const dislikeMovie = () => {
    router.post(
        route('movies.dislike.store', getItemRouteParams(props.item)),
        {},
        {
            onSuccess: () => {
                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
        },
    );
};

const loadingCollection = ref(false);

const toggleCollection = () => {
    loadingCollection.value = true;

    props.item.in_collection
        ? router.delete(route('movies.collection.destroy', [props.item]), {
              onFinish: () => {
                  loadingCollection.value = false;
              },
          })
        : router.post(
              route('movies.collection.store', [props.item]),
              {},
              {
                  onFinish: () => {
                      loadingCollection.value = false;
                  },
              },
          );
};

const loadingFavorite = ref(false);

const toggleFavorite = () => {
    loadingFavorite.value = true;

    props.item.in_favorites
        ? router.delete(route('movies.favorite.destroy', [props.item]), {
              onFinish: () => {
                  loadingFavorite.value = false;
              },
          })
        : router.post(
              route('movies.favorite.store', [props.item]),
              {},
              {
                  onFinish: () => {
                      loadingFavorite.value = false;
                  },
              },
          );
};

const loadingWishlist = ref(false);

const toggleWishlist = () => {
    loadingWishlist.value = true;

    props.item.in_wishlist
        ? router.delete(route('movies.wishlist.destroy', [props.item]), {
              onFinish: () => {
                  loadingWishlist.value = false;
              },
          })
        : router.post(
              route('movies.wishlist.store', [props.item]),
              {},
              {
                  onFinish: () => {
                      loadingWishlist.value = false;
                  },
              },
          );
};
</script>

<template>
    <div class="flex w-full flex-col">
        <div class="mb-2 flex flex-col">
            <h1
                itemprop="name"
                class="mb-4 line-clamp-2 text-ellipsis text-4xl font-extrabold"
            >
                {{ title }}
            </h1>

            <div
                class="flex flex-row content-start items-center justify-items-start gap-4"
            >
                <v-chip>
                    {{ item.type.name }}
                </v-chip>

                <v-chip v-if="props.item.is_vr">
                    {{ $t('item.vr') }}
                </v-chip>

                <v-chip v-if="props.item.is_3d">
                    {{ $t('item.3d') }}
                </v-chip>

                <span
                    v-if="item.length"
                    itemprop="duration"
                >
                    {{
                        movieDuration?.toHuman({
                            unitDisplay: 'short',
                        })
                    }}
                </span>
            </div>
        </div>

        <div class="mt-2 flex flex-row items-center justify-items-start gap-2">
            <div
                itemprop="aggregateRating"
                itemscope
                itemtype="https://schema.org/AggregateRating"
                class="flex flex-row items-center justify-items-start"
            >
                <meta
                    itemprop="ratingCount"
                    :content="
                        props.item?.love_reactant?.reaction_total?.count.toString() ??
                        '0'
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

                <user-rating
                    class="mr-2"
                    :size="64"
                    :width="6"
                    :reactable="props.item"
                    :dark="isTextWhite"
                />
            </div>

            <v-btn-toggle
                v-model="itemLike"
                divided
                rounded="xl"
                variant="tonal"
                :theme="isTextWhite ? 'dark' : 'light'"
            >
                <v-btn
                    :aria-label="
                        hasLiked
                            ? $t('movie.show.remove_like')
                            : $t('movie.show.like')
                    "
                    :icon="MdiThumbUp"
                    @click="likeMovie"
                />

                <v-btn
                    :aria-label="
                        hasDisliked
                            ? $t('movie.show.remove_dislike')
                            : $t('movie.show.dislike')
                    "
                    :icon="MdiThumbDown"
                    @click="dislikeMovie"
                />
            </v-btn-toggle>
        </div>

        <div class="mt-4 flex flex-row items-center justify-items-start gap-2">
            <v-btn
                variant="tonal"
                :loading="loadingCollection"
                @click="toggleCollection"
            >
                {{
                    props.item.in_collection
                        ? $t('movie.show.remove_from_collection')
                        : $t('movie.show.add_to_collection')
                }}
            </v-btn>

            <v-btn
                variant="tonal"
                :loading="loadingFavorite"
                @click="toggleFavorite"
            >
                {{
                    props.item.in_favorites
                        ? $t('movie.show.remove_from_favorites')
                        : $t('movie.show.add_to_favorites')
                }}
            </v-btn>

            <v-btn
                variant="tonal"
                :loading="loadingWishlist"
                @click="toggleWishlist"
            >
                {{
                    props.item.in_wishlist
                        ? $t('movie.show.remove_from_wishlist')
                        : $t('movie.show.add_to_wishlist')
                }}
            </v-btn>
        </div>
    </div>
</template>
