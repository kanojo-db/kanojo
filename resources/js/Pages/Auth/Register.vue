<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import MdiEye from '~icons/mdi/eye';
import MdiEyeOff from '~icons/mdi/eye-off';

import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Register" />

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

        <v-main>
            <v-container
                class="flex h-full flex-col items-center justify-center"
            >
                <v-row class="mb-4 grow-0">
                    <v-col>
                        <h1 class="text-3xl font-black">
                            {{ $t('general.register') }}
                        </h1>
                    </v-col>
                </v-row>

                <v-form
                    class="w-full max-w-sm"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col>
                            <v-text-field
                                id="email"
                                v-model="form.email"
                                :label="$t('general.email')"
                                type="email"
                                hint="This will be used to log in to your account."
                                required
                                :error-message="form.errors.email"
                            />
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-text-field
                                id="name"
                                v-model="form.name"
                                :label="$t('general.username')"
                                type="text"
                                hint="This will be used to identify you on the site."
                                required
                                autocomplete="name"
                                :error-message="form.errors.name"
                            />
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-text-field
                                id="password"
                                v-model="form.password"
                                :label="$t('general.password')"
                                :type="showPassword ? 'text' : 'password'"
                                hint="Your password must be at least 8 characters long."
                                required
                                autocomplete="new-password"
                                :error-message="form.errors.password"
                            >
                                <template #append-inner>
                                    <v-icon
                                        :icon="
                                            showPassword ? MdiEye : MdiEyeOff
                                        "
                                        class="cursor-pointer"
                                        @click="showPassword = !showPassword"
                                    />
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                color="primary"
                                block
                                text="Register"
                                type="submit"
                                :disabled="form.processing"
                            />
                        </v-col>
                    </v-row>
                </v-form>

                <v-row class="mt-2 grow-0">
                    <v-col>
                        {{ $t('register.alreadyUser') }}
                        <Link
                            :href="route('login')"
                            class="underline"
                        >
                            {{ $t('general.login') }}
                        </Link>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
