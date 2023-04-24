<script setup lang="ts">
import { ref } from 'vue';

import Emby from '~icons/simple-icons/emby';
import Jellyfin from '~icons/simple-icons/jellyfin';
import Kodi from '~icons/simple-icons/kodi';
import Plex from '~icons/simple-icons/plex';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const plugins = ref([
    {
        id: 1,
        icon: Plex,
        link: 'https://github.com/kanojo-db/Kanojo.bundle',
    },
    {
        id: 2,
        icon: Jellyfin,
        link: '',
    },
    {
        id: 3,
        icon: Kodi,
        link: '',
    },
    {
        id: 4,
        icon: Emby,
        link: '',
    },
]);
</script>

<template>
    <Head title="Media Server Plugins" />

    <v-row
        class="hero mb-6 h-96 p-12"
        justify="center"
        align="center"
    >
        <v-col class="hidden md:flex" />

        <v-col class="flex justify-center">
            <h1 class="max-w-4xl text-6xl font-black text-stone-50">
                Enhance your media player with top quality metadata
            </h1>
        </v-col>
    </v-row>

    <v-container>
        <v-row
            justify="center"
            align="center"
            class="flex flex-col gap-4"
        >
            <h2 class="text-4xl font-extrabold">
                Our official plugins are available for all major media players
            </h2>

            <div class="my-8 flex flex-row gap-8">
                <v-card
                    v-for="plugin in plugins"
                    :key="plugin.id"
                >
                    <v-card-title class="text-stone-900 dark:text-stone-100">
                        <component
                            :is="plugin.icon"
                            class="m-4 h-24 w-24"
                        />
                    </v-card-title>

                    <v-card-actions>
                        <v-btn
                            block
                            :disabled="!plugin.link"
                            :href="plugin.link"
                            variant="flat"
                            :text="plugin.link ? 'Download' : 'Soon'"
                            color="primary"
                            size="large"
                        />
                    </v-card-actions>
                </v-card>
            </div>
        </v-row>
    </v-container>

    <div class="bg-secondary py-4 text-stone-50">
        <v-container>
            <v-row
                justify="center"
                class="xl:gap-8"
            >
                <v-col
                    cols="12"
                    lg="5"
                    xl="5"
                    xxl="4"
                >
                    <v-img
                        class="shadow-lg"
                        src="/images/media-plugin-match.png"
                        alt="Media Plugin"
                    />
                </v-col>

                <v-col
                    cols="12"
                    lg="7"
                    xl="5"
                    xxl="4"
                >
                    <h2 class="text-7xl font-black">As easy as 1, 2, 3</h2>

                    <p class="mt-4 text-2xl">
                        Our plugins are designed to be as easy to use as
                        possible. Simply install the plugin, enter your API
                        token and you're good to go.
                    </p>

                    <p class="mt-4 text-2xl">
                        The plugin will automatically match your media with the
                        correct metadata and artwork.
                    </p>

                    <p class="mt-4 text-2xl">
                        You can also leverage our powerful search engine to fix
                        any incorrect matches.
                    </p>
                </v-col>
            </v-row>
        </v-container>
    </div>

    <div class="py-4">
        <v-container>
            <v-row
                justify="center"
                class="xl:gap-8"
            >
                <v-col
                    cols="12"
                    lg="7"
                    xl="5"
                    xxl="4"
                    :order="2"
                    :order-lg="1"
                >
                    <h2 class="text-7xl font-black">Detailed and accurate</h2>

                    <p class="mt-4 text-2xl">
                        Our plugins are tailored to each media player to ensure
                        the best possible experience.
                    </p>

                    <p class="mt-4 text-2xl">
                        We provide detailed metadata for all your content,
                        including cast, director, genres, release dates, artwork
                        and more.
                    </p>

                    <p class="mt-4 text-2xl">
                        We make sure to provide all the information you need to
                        make your media player look great and find the content
                        you want to watch.
                    </p>
                </v-col>

                <v-col
                    cols="12"
                    lg="5"
                    xl="5"
                    xxl="4"
                    :order="1"
                    :order-lg="2"
                >
                    <v-img
                        class="shadow-lg"
                        src="/images/media-plugin-cast.png"
                        alt="Media Plugin"
                    />
                </v-col>
            </v-row>
        </v-container>
    </div>

    <div class="bg-primary p-16">
        <v-row
            justify="center"
            align="center"
            class="flex flex-col gap-4"
        >
            <h2 class="text-4xl font-extrabold">Download now</h2>

            <div class="my-8 flex flex-row gap-8">
                <v-card
                    v-for="plugin in plugins"
                    :key="plugin.id"
                >
                    <v-card-title class="text-stone-900 dark:text-stone-100">
                        <component
                            :is="plugin.icon"
                            class="m-4 h-24 w-24"
                        />
                    </v-card-title>

                    <v-card-actions>
                        <v-btn
                            block
                            :disabled="!plugin.link"
                            :href="plugin.link"
                            variant="flat"
                            :text="plugin.link ? 'Download' : 'Soon'"
                            color="primary"
                            size="large"
                        />
                    </v-card-actions>
                </v-card>
            </div>
        </v-row>
    </div>
</template>
