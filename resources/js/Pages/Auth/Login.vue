<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

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
                            {{ $t('general.login') }}
                        </h1>
                    </v-col>
                </v-row>

                <v-form
                    class="w-full max-w-sm"
                    @submit.prevent="submit"
                >
                    <v-text-field
                        id="email"
                        v-model="form.email"
                        class="q-mb-sm"
                        type="email"
                        label="Email"
                        filled
                        required
                        :error="!!form?.errors?.email"
                        :error-message="form.errors.email"
                    />

                    <v-text-field
                        id="password"
                        v-model="form.password"
                        type="password"
                        label="Password"
                        filled
                        required
                        autocomplete="current-password"
                        :error="!!form?.errors?.password"
                        :error-message="form.errors.password"
                    />

                    <v-checkbox
                        v-model="form.remember"
                        name="remember"
                        label="Remember me"
                    />

                    <v-btn
                        color="primary"
                        :text="$t('login.submit')"
                        type="submit"
                        block
                        variant="flat"
                        :disabled="form.processing"
                    />
                </v-form>

                <Link
                    :href="route('password.request')"
                    class="mt-6 text-right underline hover:text-primary"
                >
                    Forgot your password?
                </Link>

                <div class="mt-2">
                    {{ $t('login.needAccount') }}
                    <Link
                        :href="route('register')"
                        class="underline hover:text-primary"
                    >
                        {{ $t('general.register') }}
                    </Link>
                </div>
            </v-container>
        </v-main>
    </v-app>
</template>
