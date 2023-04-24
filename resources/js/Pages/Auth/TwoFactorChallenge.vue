<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const recovery = ref(false);

const form = useForm({
    code: '',
    recovery_code: '',
});

const toggleRecovery = () => {
    recovery.value = !recovery.value;
};

const submit = () => {
    form.post(route('two-factor.login'));
};
</script>

<template>
    <Head title="Two-factor Confirmation" />

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
                            <template v-if="!recovery">
                                <v-otp-input
                                    id="code"
                                    v-model="form.code"
                                    autocomplete="one-time-code"
                                    :loading="form.processing"
                                    @finish="submit"
                                />
                            </template>

                            <template v-else>
                                <v-text-field
                                    id="recovery_code"
                                    v-model="form.recovery_code"
                                    type="text"
                                    autocomplete="one-time-code"
                                />
                            </template>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                block
                                type="button"
                                @click.prevent="toggleRecovery"
                            >
                                <template v-if="!recovery">
                                    {{ $t('general.useRecoveryCode') }}
                                </template>

                                <template v-else>
                                    {{ $t('general.useTwoFactorCode') }}
                                </template>
                            </v-btn>
                        </v-col>
                    </v-row>

                    <v-row v-if="recovery">
                        <v-col>
                            <v-btn
                                block
                                type="submit"
                                color="primary"
                                :text="$t('general.login')"
                                :disabled="form.processing"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-main>
    </v-app>
</template>
