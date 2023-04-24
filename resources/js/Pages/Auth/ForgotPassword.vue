<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
        required: true,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <Head title="Forgot Password" />

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
                <v-alert
                    v-if="status"
                    class="mb-2 grow-0"
                    :text="status"
                />

                <v-form
                    class="w-full max-w-sm"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col>
                            <p>
                                {{ $t('general.forgotPassword') }}
                            </p>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-text-field
                                id="email"
                                v-model="form.email"
                                type="email"
                                :label="$t('general.email')"
                                required
                                :error-message="form.errors.email"
                            />
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                block
                                color="primary"
                                type="submit"
                                :text="$t('general.sendPasswordResetLink')"
                                :disabled="form.processing"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-main>
    </v-app>
</template>
