<script setup>
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Register" />

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

                            <q-form @submit.prevent="submit">
                                <q-input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    required
                                    filled
                                    autofocus
                                    bottom-slots
                                    autocomplete="name"
                                    label="Username"
                                    :error="!!form?.errors?.name"
                                    :error-message="form.errors.name"
                                />

                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    filled
                                    required
                                    bottom-slots
                                    label="Email"
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />

                                <q-input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    filled
                                    required
                                    bottom-slots
                                    autocomplete="new-password"
                                    label="Password"
                                    :error="!!form?.errors?.password"
                                    :error-message="form.errors.password"
                                />

                                <q-input
                                    id="password_confirmation"
                                    v-model="form.password_confirmation"
                                    type="password"
                                    filled
                                    required
                                    bottom-slots
                                    autocomplete="new-password"
                                    label="Confirm Password"
                                    :error="
                                        !!form?.errors?.password_confirmation
                                    "
                                    :error-message="
                                        form.errors.password_confirmation
                                    "
                                />

                                <q-card-actions>
                                    <Link
                                        :href="route('login')"
                                        class="underline text-sm text-gray-600 hover:text-gray-900"
                                    >
                                        Already registered?
                                    </Link>

                                    <q-space />

                                    <q-btn
                                        color="primary"
                                        label="Register"
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
