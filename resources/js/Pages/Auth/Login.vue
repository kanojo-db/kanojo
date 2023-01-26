<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

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

    <q-layout view="hHh lpR fFf">
        <q-page-container>
            <q-page style="background-color: #1a281f">
                <div class="row items-center justify-center m-t-none m-b-none">
                    <div class="col col-md-3">
                        <q-card
                            flat
                            bordered
                            class="my-card bg-grey-1"
                        >
                            <div class="q-pa-lg">
                                <q-img
                                    src="/images/logo-light.svg"
                                    height="72px"
                                    fit="contain"
                                />
                            </div>

                            <div
                                v-if="status"
                                class="mb-4 font-medium text-sm text-green-600"
                            >
                                {{ status }}
                            </div>

                            <q-form @submit.prevent="submit">
                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    filled
                                    required
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />

                                <q-input
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

                                <q-checkbox
                                    v-model="form.remember"
                                    name="remember"
                                    label="Remember me"
                                />

                                <q-card-actions>
                                    <Link
                                        v-if="props.canResetPassword"
                                        :href="route('password.request')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900"
                                    >
                                        Forgot your password?
                                    </Link>

                                    <q-space />

                                    <q-btn
                                        color="primary"
                                        label="Login"
                                        type="submit"
                                        :disabled="form.processing"
                                    />
                                </q-card-actions>
                            </q-form>
                        </q-card>
                    </div>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
