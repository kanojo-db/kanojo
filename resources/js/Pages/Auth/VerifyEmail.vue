<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    status: {
        type: String,
        required: true,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <Head title="Email Verification" />

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
                            To complete the registration process, we need to
                            verify your email address. We have sent a
                            verification link to the email address you provided
                            during registration. Please check your inbox
                            (including spam folder) for an email from us and
                            click on the verification link within 24 hours.
                        </div>

                        <div
                            v-if="verificationLinkSent"
                            class="q-mb-md text-white bg-grey-6 q-pa-md rounded-borders"
                        >
                            A new verification link has been sent to the email
                            address you provided during registration.
                        </div>

                        <form @submit.prevent="submit">
                            <div class="row justify-center">
                                <q-btn
                                    color="primary"
                                    type="submit"
                                    :disabled="form.processing"
                                >
                                    Resend Verification Email
                                </q-btn>
                            </div>
                        </form>
                    </div>
                </q-card>
            </q-page>
        </q-page-container>
    </q-layout>
</template>
