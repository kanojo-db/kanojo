<script setup lang="ts">
import { router } from '@inertiajs/vue3';

import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const submitForm = () => {
    router.post(route('age_gate.store'));
};
</script>

<template>
    <v-app>
        <v-app-bar
            flat
            class="bg-primary"
        >
            <v-app-bar-title class="mr-2 max-w-[130px]">
                <img
                    alt="Back to home"
                    src="/images/logo-dark.svg"
                    class="h-[36px] w-[130px] object-contain"
                />
            </v-app-bar-title>

            <template #append>
                <language-switcher />
            </template>
        </v-app-bar>

        <div class="mx-4 mt-4 flex h-full flex-col items-center justify-center">
            <div class="prose mb-8">
                <h1 class="text-3xl font-black">
                    This website contains adult content!
                </h1>

                <p>
                    Some of the items on this website feature nudity and/or
                    sexually explicit content.
                </p>

                <p>
                    By entering this website you agree that you are at least 18
                    years old or the legal age to view adult content in your
                    country.
                </p>

                <p>
                    This website does not provide any content itself and acts as
                    a database and search engine for Japanese adult content and
                    gravure idols.
                </p>
            </div>

            <v-form @submit.prevent="submitForm">
                <v-btn
                    color="primary"
                    type="submit"
                >
                    Enter
                </v-btn>
            </v-form>
        </div>
    </v-app>
</template>
