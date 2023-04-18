<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const featuredArticles = [
    {
        category: 'Movies',
        title: 'List of banned titles',
        link: '#',
    },
    {
        category: 'General',
        title: 'Content guidelines',
        link: '#',
    },
    {
        category: 'General',
        title: 'Translating content',
        link: '#',
    },
    {
        category: 'Movies',
        title: 'Adding new movies',
        link: '#',
    },
    {
        category: 'Models',
        title: 'Handling aliases',
        link: '#',
    },
    {
        category: 'Studios',
        title: 'Studios, labels and distributors',
        link: '#',
    },
    {
        category: 'Movies',
        title: 'Special product codes',
        link: '#',
    },
    {
        category: 'Images',
        title: 'Image guidelines',
        link: '#',
    },
];

const categoryColors = {
    General: 'bg-primary',
    Images: 'bg-secondary',
    Movies: 'bg-accent',
    Models: 'bg-green',
    Studios: 'bg-blue',
};
</script>

<template>
    <div class="flex column items-center justify-center q-py-xl no-wrap">
        <h1 class="text-h1 text-weight-bolder q-mb-xl">Contribution Bible</h1>
        <div class="content-wrapper q-mt-xl">
            <div class="row full-width">
                <Link
                    class="col col-6"
                    :href="route('bible.general')"
                >
                    <q-card class="bg-primary q-px-xl q-py-xl q-ma-md">
                        <p
                            class="text-h1 text-white text-weight-bolder q-mt-xl"
                        >
                            General
                        </p>
                        <hr
                            class="q-mb-xl"
                            style="border: 6px solid white"
                        />
                    </q-card>
                </Link>

                <Link
                    class="col col-6"
                    href="#"
                >
                    <q-card class="bg-secondary q-px-xl q-py-xl q-ma-md">
                        <p
                            class="text-h1 text-white text-weight-bolder q-mt-xl"
                        >
                            Images
                        </p>
                        <hr
                            class="q-mb-xl"
                            style="border: 6px solid white"
                        />
                    </q-card>
                </Link>
            </div>
            <div class="row full-width">
                <Link
                    class="col col-4"
                    href="#"
                >
                    <q-card class="bg-accent q-px-xl q-py-xl q-ma-md">
                        <p class="text-h2 text-white text-weight-bolder">
                            Movies
                        </p>
                        <hr style="border: 6px solid white" />
                    </q-card>
                </Link>

                <Link
                    class="col col-4"
                    href="#"
                >
                    <q-card class="bg-green q-px-xl q-py-xl q-ma-md">
                        <p class="text-h2 text-white text-weight-bolder">
                            Models
                        </p>
                        <hr style="border: 6px solid white" />
                    </q-card>
                </Link>

                <Link
                    class="col col-4"
                    href="#"
                >
                    <q-card class="bg-blue q-px-xl q-py-xl q-ma-md">
                        <p class="text-h2 text-white text-weight-bolder">
                            Studios
                        </p>
                        <hr style="border: 6px solid white" />
                    </q-card>
                </Link>
            </div>
            <div class="row full-width">
                <div class="col">
                    <h2 class="text-h2 text-weight-bolder q-mx-md q-mt-xl">
                        Featured Articles
                    </h2>
                    <div class="grid-2">
                        <div
                            v-for="article in featuredArticles"
                            :key="article.title"
                            class="row items-center col col-6"
                            style="border-bottom: 1px solid #d1d4d2"
                        >
                            <q-chip
                                class="q-ma-md"
                                :class="categoryColors[article.category]"
                                text-color="white text-overline font-weight-bold"
                                size="lg"
                            >
                                {{ article.category }}
                            </q-chip>
                            <Link :href="article.link">
                                <h3 class="text-h5 text-weight-bolder">
                                    {{ article.title }}
                                </h3>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
