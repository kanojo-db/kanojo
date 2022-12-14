<script setup>
import { Inertia } from '@inertiajs/inertia';
import { ref } from 'vue';

import ModelCard from '@/Components/ModelCard.vue';
import MovieCard from '@/Components/MovieCard.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    moviesResults: {
        type: Object,
        required: true,
    },
    modelsResults: {
        type: Object,
        required: true,
    },
});

let results = {};

if (route().params.type === 'person') {
    // eslint-disable-next-line vue/no-setup-props-destructure -- Intentional
    results = props.modelsResults;
} else {
    // eslint-disable-next-line vue/no-setup-props-destructure -- Intentional
    results = props.moviesResults;
}

const currentPage = ref(results.current_page);

const goToType = (type) => {
    Inertia.get('search', { type, q: route().params.q });
};

const goToPage = (page) => {
    const pageLink = results.links.find((link) => link.label == page);

    if (pageLink && pageLink.url) {
        Inertia.get(pageLink.url);
    }
};
</script>

<template>
    <AppLayout>
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
                                        route().params.type !== 'person',
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
                                        {{ movies_results.total }}
                                    </q-badge>
                                </q-item-section>
                            </q-item>
                            <q-item
                                clickable
                                :class="{
                                    'text-primary text-weight-bold':
                                        route().params.type === 'person',
                                }"
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
                                        {{ models_results.total }}
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
                                v-if="route().params.type === 'person'"
                                class="fit row wrap justify-start items-start content-start q-gutter-md"
                            >
                                <ModelCard
                                    v-for="model in results.data"
                                    :key="model.id"
                                    :model="model"
                                />
                            </div>
                            <div
                                v-else
                                class="fit row wrap justify-start items-start content-start q-gutter-md"
                            >
                                <MovieCard
                                    v-for="movie in results.data"
                                    :key="movie.id"
                                    :movie="movie"
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
    </AppLayout>
</template>
