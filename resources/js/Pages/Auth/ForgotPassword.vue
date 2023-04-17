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

    <q-layout view="hHh lpR fFf">
        <q-page-container>
            <q-page class="bg-grey-8 row no-wrap items-center justify-center">
                <q-card class="col-4 bg-grey-1">
                    <div class="self-start bg-grey-2 full-width q-px-md">
                        <q-img
                            src="/images/logo-light.svg"
                            ratio="2"
                            position="50% 50%"
                            fit="contain"
                            width="10em"
                        />
                    </div>

                    <div class="q-pa-md">
                        <div class="q-mb-md">
                            Forgot your password? No problem. Just let us know
                            your email address and we will email you a password
                            reset link that will allow you to choose a new one.
                        </div>

                        <div
                            v-if="status"
                            class="q-mb-md text-white bg-grey-6 q-pa-md rounded-borders"
                        >
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit">
                            <div>
                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    required
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <q-btn
                                    class="q-mt-md"
                                    color="primary"
                                    type="submit"
                                    :disable="form.processing"
                                    :disabled="form.processing"
                                >
                                    Email Password Reset Link
                                </q-btn>
                            </div>
                        </form>
                    </div>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
