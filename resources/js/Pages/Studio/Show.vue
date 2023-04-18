<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import CardSwiper from '@/Components/CardSwiper.vue';
import MovieCard from '@/Components/MovieCard.vue';
import StudioTabBar from '@/Components/StudioTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Movie, Paginated, People, Studio } from '@/types/models';
import { useName } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    studio: {
        type: Object as PropType<Studio>,
        required: true,
    },
    movies: {
        type: Object as PropType<Paginated<Movie>>,
        required: true,
    },
    models: {
        type: Array as PropType<People>,
        required: true,
    },
    movieCount: {
        type: Number,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.studio, locale);

const currentPage = ref(props.movies.current_page);

const goToPage = (page: number) => {
    const pageLink = props.movies.links.find(
        (link) => link.label === page.toString(),
    );

    if (pageLink && pageLink.url) {
        router.get(pageLink.url, undefined, {
            only: ['movies'],
            preserveScroll: true,
            preserveState: true,
        });
    }
};
</script>

<template>
    <div class="col bg-grey-3 q-pb-lg">
        <StudioTabBar :studio="studio" />
        <div class="row q-pt-lg q-px-md">
            <div class="col">
                <div class="row q-mb-sm">
                    <h1 class="text-h4 q-mt-none q-mb-sm ellipsis-2-lines">
                        {{ name }}
                        <q-badge
                            class="text-subtitle1"
                            align="middle"
                            color="grey-7"
                        >
                            {{
                                $t('web.personShow.moviesCount', {
                                    number: movieCount.toLocaleString(),
                                })
                            }}
                        </q-badge>
                    </h1>
                </div>
            </div>
        </div>
        <div
            v-if="props.models.length > 0"
            class="q-px-md"
        >
            <div class="row">
                <h2 class="text-h5 q-mt-none">
                    {{ $t('web.studio.known_for') }}
                </h2>
            </div>
            <section>
                <CardSwiper :items="props.models" />
            </section>
        </div>
    </div>
    <div class="q-pa-md">
        <div class="row justify-center q-mb-md">
            <q-pagination
                v-model="currentPage"
                :max="props.movies.last_page"
                :max-pages="6"
                boundary-numbers
                @update:model-value="goToPage"
            />
        </div>
        <div class="card-grid-container">
            <div
                v-for="movie in props.movies.data"
                :key="movie.id"
            >
                <MovieCard :movie="movie" />
            </div>
        </div>
        <div class="row justify-center q-mb-md">
            <q-pagination
                v-model="currentPage"
                :max="props.movies.last_page"
                :max-pages="6"
                boundary-numbers
                @update:model-value="goToPage"
            />
        </div>
    </div>
</template>
