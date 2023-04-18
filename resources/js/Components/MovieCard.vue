<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { PropType, computed } from 'vue';
import { useI18n } from 'vue-i18n';

import { Movie } from '@/types/models';
import { useFirstImage, useTitle } from '@/utils/item';

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const isVrMovie = computed(() => {
    return props.movie.type.name === 'VR' || props.movie.type_id === 4;
});

const locale = useI18n().locale.value;

const title = useTitle(props.movie, locale);

const posterUrl = useFirstImage(props.movie);

const { t } = useI18n();

const averageScore = computed(() => {
    if (props.movie?.love_reactant?.reaction_total?.count) {
        const likeReactions = props.movie?.love_reactant?.reactions?.filter(
            (reaction) => reaction.type.name === 'Like',
        );

        return (
            (likeReactions.length /
                props.movie?.love_reactant?.reaction_total?.count) *
            100
        );
    }

    return 0;
});

const userScore = computed(() => {
    if (props.movie?.love_reactant?.reaction_total?.count) {
        return averageScore.value;
    }

    return t('web.general.not_rated');
});
</script>

<template>
    <Link
        class="block"
        :href="route('movies.show', { movie: movie.slug })"
        style="width: 200px"
    >
        <div class="fit column no-wrap justify-start items-start content-start">
            <q-img
                v-if="posterUrl"
                :src="posterUrl"
                width="200px"
                :ratio="2 / 3"
                :fit="isVrMovie ? 'contain' : 'cover'"
                class="rounded-borders bg-grey-1 shadow-1"
            />
            <div
                v-else
                class="row bg-grey-1 rounded-borders justify-center items-center shadow-1"
                style="width: 200px; height: 300px"
            >
                <q-icon
                    name="mdi-help"
                    size="150px"
                    color="grey-2"
                />
            </div>
            <div
                class="fit column no-wrap justify-start items-start content-start relative-position q-pt-sm"
            >
                <q-knob
                    :value="averageScore"
                    readonly
                    :min="0"
                    :max="10"
                    show-value
                    size="50px"
                    :thickness="0.15"
                    color="primary"
                    center-color="grey-2"
                    track-color="grey-2"
                    class="absolute"
                    style="top: -25px"
                    :model-value="averageScore"
                >
                    <div
                        class="row justify-center items-center text-overline text-grey-7"
                    >
                        <i18n-t
                            v-if="averageScore > 0"
                            keypath="web.general.x_percent"
                        >
                            <template #number>
                                <span
                                    class="text-weight-bolder text-body1 q-mt-none"
                                    >{{ userScore }}</span
                                >
                            </template>
                        </i18n-t>
                        <span
                            v-else
                            class="text-weight-bolder text-body1 q-ma-none"
                        >
                            {{ $t('web.general.not_rated') }}
                        </span>
                    </div>
                </q-knob>
                <span class="text-weight-bold q-mt-lg ellipsis-2-lines">{{
                    title
                }}</span>
                <span class="q-mb-sm">{{ movie.product_code }}</span>
            </div>
        </div>
    </Link>
</template>
