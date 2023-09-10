<script setup lang="ts">
import { router, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiMagnify from '~icons/mdi/magnify';

const props = defineProps({
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

const { locale } = useI18n();

const searchForm = useForm({
    q: '',
});

const submit = () => {
    router.get(
        route('search', {
            q: searchForm.q.trim(),
        }),
    );
};
</script>

<template>
    <div class="select-white flex bg-primary text-stone-50">
        <v-container class="my-8 lg:my-24">
            <v-row class="items-center justify-center">
                <v-col>
                    <h1
                        class="lg:mx-none mx-2 mb-6 text-center text-3xl font-black md:text-4xl lg:mb-12 lg:text-5xl xl:text-6xl"
                    >
                        {{ $t('welcome.slogan') }}
                    </h1>
                </v-col>
            </v-row>

            <v-row
                class="mb-6 lg:mb-12"
                justify="center"
            >
                <v-col
                    :cols="12"
                    :lg="8"
                    :xl="6"
                    :xxl="4"
                    class="items-center justify-center"
                >
                    <v-text-field
                        v-model="searchForm.q"
                        rounded
                        outlined
                        variant="solo"
                        single-line
                        hide-details
                        placeholder="Search for a movie or model..."
                        :error-message="searchForm.errors?.q"
                        @keyup.enter="submit"
                    >
                        <template #append-inner>
                            <v-btn
                                class="absolute right-0 mr-1"
                                variant="flat"
                                color="primary"
                                :icon="MdiMagnify"
                                aria-label="Search"
                                @click="submit"
                            />
                        </template>
                    </v-text-field>
                </v-col>
            </v-row>

            <v-row justify="center">
                <v-col
                    :cols="12"
                    :md="4"
                    :lg="3"
                    :xl="2"
                >
                    <div class="flex flex-col place-items-center">
                        <span
                            class="text-center text-2xl font-bold uppercase lg:text-3xl"
                        >
                            {{ $t('welcome.countMovies') }}
                        </span>

                        <span class="text-4xl font-black lg:text-5xl">
                            {{ props.movieCount.toLocaleString(locale) }}
                        </span>
                    </div>
                </v-col>

                <v-col
                    :cols="12"
                    :md="4"
                    :lg="3"
                    :xl="2"
                >
                    <div class="flex flex-col place-items-center">
                        <span
                            class="text-center text-2xl font-bold uppercase lg:text-3xl"
                        >
                            {{ $t('welcome.countModels') }}
                        </span>

                        <span class="text-4xl font-black lg:text-5xl">
                            {{ props.modelCount.toLocaleString(locale) }}
                        </span>
                    </div>
                </v-col>

                <v-col
                    :cols="12"
                    :md="4"
                    :lg="3"
                    :xl="2"
                >
                    <div class="flex flex-col place-items-center">
                        <span
                            class="text-center text-2xl font-bold uppercase lg:text-3xl"
                        >
                            {{ $t('welcome.countCategories') }}
                        </span>

                        <span class="text-4xl font-black lg:text-5xl">
                            {{ props.tagCount.toLocaleString(locale) }}
                        </span>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
