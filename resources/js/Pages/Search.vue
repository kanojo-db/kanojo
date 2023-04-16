<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { PropType, computed, reactive, ref } from 'vue';

import ModelCard from '@/Components/ModelCard.vue';
import MovieCard from '@/Components/MovieCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Movie, Paginated } from '@/types/models';
import { Person } from '@/types/models';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    moviesResults: {
        type: Object as PropType<Paginated<Movie>>,
        required: true,
    },
    modelsResults: {
        type: Object as PropType<Paginated<Person>>,
        required: true,
    },
});

let results = {} as Paginated<Movie | Person>;

const routeParams = route().params;

const resultType = computed(() => {
    if (routeParams.type === 'person') {
        return 'models';
    }

    return 'movies';
});

if (resultType.value === 'models') {
    results = reactive(props.modelsResults);
} else {
    results = reactive(props.moviesResults);
}

const currentPage = ref(results.current_page);

const goToType = (type: string) => {
    router.get('search', { type, q: route().params.q });
};

const goToPage = (page: number) => {
    const pageLink = results.links.find(
        (link) => link.label === page.toString(),
    );

    if (pageLink && pageLink.url) {
        router.get(pageLink.url);
    }
};
</script>

<template>
    <div class="q-pa-md">
        <div class="row q-col-gutter-lg full-width">
            <div class="col-2 q-pl-none">
                <q-card
                    class="my-card"
                    flat
                    bordered
                >
                    <q-card-section
                        class="bg-primary text-white row items-center"
                    >
                        <div class="text-weight-bold text-h6">
                            {{ $t('web.general.pages.search') }}
                        </div>
                    </q-card-section>

                    <q-separator />

                    <q-list
                        dense
                        bordered
                        padding
                    >
                        <q-item
                            clickable
                            :class="{
                                'text-primary text-weight-bold':
                                    routeParams.type !== 'person',
                            }"
                            @click="() => goToType('movie')"
                        >
                            <q-item-section>
                                {{ $t('web.search.result_types.movies') }}
                            </q-item-section>
                            <q-item-section side>
                                <q-badge
                                    class="text-subtitle1 q-px-md"
                                    align="middle"
                                    color="grey-5"
                                    rounded
                                >
                                    {{ moviesResults.total }}
                                </q-badge>
                            </q-item-section>
                        </q-item>
                        <q-item
                            clickable
                            :class="{
                                'text-primary text-weight-bold':
                                    routeParams.type === 'person',
                            }"
                            @click="() => goToType('person')"
                        >
                            <q-item-section>
                                {{ $t('web.search.result_types.models') }}
                            </q-item-section>
                            <q-item-section side>
                                <q-badge
                                    class="text-subtitle1 q-px-md"
                                    align="middle"
                                    color="grey-5"
                                    rounded
                                    @click="() => goToType('person')"
                                >
                                    {{ modelsResults.total }}
                                </q-badge>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-10">
                <div class="row justify-center q-mb-md">
                    <q-pagination
                        v-model="currentPage"
                        :max="results.last_page"
                        :max-pages="6"
                        boundary-numbers
                        @update:model-value="goToPage"
                    />
                </div>
                <div
                    v-if="results.data.length === 0"
                    class="text-center"
                >
                    <h1 class="text-h5">
                        {{ $t('web.search.no_results') }}
                    </h1>
                </div>
                <div v-else>
                    <div class="q-pa-md">
                        <div
                            v-if="routeParams.type === 'person'"
                            class="fit row wrap justify-start items-start content-start q-gutter-md"
                        >
                            <ModelCard
                                v-for="model in results.data"
                                :key="model.id"
                                :model="model as Person"
                            />
                        </div>
                        <div
                            v-else
                            class="fit row wrap justify-start items-start content-start q-gutter-md"
                        >
                            <MovieCard
                                v-for="movie in results.data"
                                :key="movie.id"
                                :movie="movie as Movie"
                            />
                        </div>
                    </div>
                </div>
                <div class="row justify-center q-mb-md">
                    <q-pagination
                        v-model="currentPage"
                        :max="results.last_page"
                        :max-pages="6"
                        boundary-numbers
                        @update:model-value="goToPage"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
