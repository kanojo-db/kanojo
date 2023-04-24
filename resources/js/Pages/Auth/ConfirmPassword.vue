<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Secure Area" />

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
                <v-form
                    class="w-full max-w-sm"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col>
                            <v-text-field
                                id="password"
                                v-model="form.password"
                                type="password"
                                required
                                autocomplete="current-password"
                                :label="$t('general.password')"
                                :error-messages="form.errors.password"
                            />
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                block
                                color="primary"
                                type="submit"
                                :text="$t('general.confirmPassword')"
                                :disabled="form.processing"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-main>
    </v-app>
</template>
