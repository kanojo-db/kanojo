<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    canResetPassword: {
        type: Boolean,
        required: true,
    },
    status: {
        type: String,
        default: '',
    },
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
            <q-page class="bg-grey-8 row no-wrap items-stretch">
                <div
                    flat
                    bordered
                    class="bg-grey-1 col-5 column flex justify-center items-center"
                >
                    <div class="self-start bg-grey-2 full-width q-px-md">
                        <q-img
                            src="/images/logo-light.svg"
                            ratio="2"
                            position="50% 50%"
                            fit="contain"
                            width="10em"
                        />
                    </div>
                    <div
                        class="row col-grow justify-center items-center full-width"
                    >
                        <div>
                            <h1
                                class="text-h2 text-grey-9 text-weight-bolder q-text-center q-mt-none q-mb-md"
                            >
                                {{ $t('web.login.welcomeBack') }}
                            </h1>
                            <div class="col-8">
                                <q-form
                                    class="col-grow q-pa-xl"
                                    @submit.prevent="submit"
                                >
                                    <div
                                        v-if="status"
                                        class="mb-4 font-medium text-sm text-green-600"
                                    >
                                        {{ status }}
                                    </div>

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
                                        :label="$t('web.login.submit')"
                                        type="submit"
                                        class="full-width q-mt-md"
                                        :disabled="form.processing"
                                    />
                                </q-form>
                            </div>
                        </div>
                    </div>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
