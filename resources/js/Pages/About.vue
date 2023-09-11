<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head :title="$t('general.pages.about')" />

    <div
        class="flex flex-col items-center justify-center bg-stone-600 p-4 md:p-12"
    >
        <v-container>
            <v-row justify="center">
                <v-col
                    :cols="12"
                    :lg="11"
                    :xl="10"
                    :xxl="6"
                    class="flex flex-col justify-center gap-8"
                >
                    <i18n-t
                        keypath="about.title"
                        tag="h1"
                        for="community"
                        class="text-center text-6xl font-thin text-stone-50"
                    >
                        <span class="font-normal">
                            {{ $t('about.community') }}
                        </span>
                    </i18n-t>

                    <i18n-t
                        keypath="about.subtitle"
                        tag="p"
                        class="text-center text-2xl text-stone-50"
                    >
                        <br />
                    </i18n-t>

                    <div class="mt-8 flex flex-col items-center">
                        <div
                            class="flex flex-row gap-8 text-stone-50"
                            style="width: 45em; height: 6.5em"
                        >
                            <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                            <div class="text-6xl font-black text-secondary">
                                1
                            </div>

                            <div>
                                {{ $t('about.point1') }}
                            </div>
                        </div>

                        <div
                            class="flex flex-row gap-8 text-stone-50"
                            style="width: 45em; height: 6.5em"
                        >
                            <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                            <div class="text-6xl font-black text-secondary">
                                2
                            </div>

                            <div>
                                {{ $t('about.point2') }}
                            </div>
                        </div>

                        <div
                            class="flex flex-row gap-8 text-stone-50"
                            style="width: 45em; height: 6.5em"
                        >
                            <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                            <div class="text-6xl font-black text-secondary">
                                3
                            </div>

                            <div>
                                {{ $t('about.point3') }}
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>

            <v-row justify="center">
                <v-col
                    :cols="12"
                    class="flex justify-center"
                >
                    <v-btn
                        color="primary"
                        text="Contact Us"
                        href="mailto:contact@kanojodb.com"
                    />
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>
