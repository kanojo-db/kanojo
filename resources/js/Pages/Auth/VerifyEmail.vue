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
                    v-if="verificationLinkSent"
                    class="mb-2 grow-0"
                    :text="$t('general.verificationLinkSent')"
                />

                <v-row>
                    <v-col>
                        <p :v-text="$t('general.verifyEmail')" />
                    </v-col>
                </v-row>

                <v-form
                    class="w-full max-w-sm"
                    @submit.prevent="submit"
                >
                    <v-row>
                        <v-col>
                            <v-btn
                                color="primary"
                                block
                                type="submit"
                                :disabled="form.processing"
                            >
                                {{ $t('general.resendVerificationEmail') }}
                            </v-btn>
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-main>
    </v-app>
</template>
