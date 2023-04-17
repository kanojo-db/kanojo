<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { mdiEye, mdiEyeOff } from '@quasar/extras/mdi-v6';
import { ref } from 'vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const showPassword = ref(false);

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Reset Password" />

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
                        <form @submit.prevent="submit">
                            <div>
                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    label="Email"
                                    class="q-mb-md"
                                    required
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />
                            </div>

                            <div class="mt-4">
                                <q-input
                                    id="password"
                                    v-model="form.password"
                                    class="q-mb-md"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="new-password"
                                    :error="!!form?.errors?.password"
                                    :error-message="form.errors.password"
                                >
                                    <template #append>
                                        <q-icon
                                            :name="
                                                showPassword
                                                    ? mdiEye
                                                    : mdiEyeOff
                                            "
                                            class="cursor-pointer"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                        />
                                    </template>
                                </q-input>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <q-btn
                                    type="submit"
                                    color="primary"
                                    :disabled="form.processing"
                                >
                                    Reset Password
                                </q-btn>
                            </div>
                        </form>
                    </div>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
