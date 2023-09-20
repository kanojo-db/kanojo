<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const loading = ref(false);

const submitForm = () => {
    loading.value = true;

    router.post(
        route('age_gate.store'),
        {},
        {
            onSuccess: () => {
                loading.value = false;
            },
        },
    );
};
</script>

<template>
    <Head :title="$t('general.pages.age_gate')" />

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
                    {{ $t('general.pages.ageGate.title') }}
                </h1>

                <p>
                    {{ $t('general.pages.ageGate.description1') }}
                </p>

                <p>
                    {{ $t('general.pages.ageGate.description2') }}
                </p>

                <p>
                    {{ $t('general.pages.ageGate.description3') }}
                </p>
            </div>

            <v-form @submit.prevent="submitForm">
                <v-btn
                    color="primary"
                    :loading="loading"
                    type="submit"
                >
                    {{ $t('general.pages.ageGate.confirm') }}
                </v-btn>
            </v-form>
        </div>
    </v-app>
</template>
