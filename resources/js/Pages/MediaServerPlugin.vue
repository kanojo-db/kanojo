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
                {{ $t('general.mediaServerPlugins.title') }}
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
                {{ $t('general.mediaServerPlugins.description') }}
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
                            :text="
                                plugin.link
                                    ? $t('general.download')
                                    : $t('general.comingSoon')
                            "
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
                    <h2 class="text-7xl font-black">
                        {{ $t('general.mediaServerPlugins.match.title') }}
                    </h2>

                    <p class="mt-4 text-2xl">
                        {{
                            $t('general.mediaServerPlugins.match.description1')
                        }}
                    </p>

                    <p class="mt-4 text-2xl">
                        {{
                            $t('general.mediaServerPlugins.match.description2')
                        }}
                    </p>

                    <p class="mt-4 text-2xl">
                        {{
                            $t('general.mediaServerPlugins.match.description3')
                        }}
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
                    <h2 class="text-7xl font-black">
                        {{ $t('general.mediaServerPlugins.metadata.title') }}
                    </h2>

                    <p class="mt-4 text-2xl">
                        {{
                            $t(
                                'general.mediaServerPlugins.metadata.description1',
                            )
                        }}
                    </p>

                    <p class="mt-4 text-2xl">
                        {{
                            $t(
                                'general.mediaServerPlugins.metadata.description2',
                            )
                        }}
                    </p>

                    <p class="mt-4 text-2xl">
                        {{
                            $t(
                                'general.mediaServerPlugins.metadata.description3',
                            )
                        }}
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
            <h2 class="text-4xl font-extrabold">
                {{ $t('general.mediaServerPlugins.downloadNow') }}
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
                            :text="
                                plugin.link
                                    ? $t('general.download')
                                    : $t('general.comingSoon')
                            "
                            color="primary"
                            size="large"
                        />
                    </v-card-actions>
                </v-card>
            </div>
        </v-row>
    </div>
</template>
