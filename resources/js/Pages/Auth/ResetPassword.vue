<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import MdiEye from '~icons/mdi/eye';
import MdiEyeOff from '~icons/mdi/eye-off';

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
    <Head :title="$t('pages.resetPassword')" />

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
                            <v-text-field
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                :label="$t('general.newPassword')"
                                autocomplete="new-password"
                                :error-message="form.errors.password"
                            >
                                <template #append>
                                    <q-icon
                                        :name="
                                            showPassword ? MdiEye : MdiEyeOff
                                        "
                                        class="cursor-pointer"
                                        @click="showPassword = !showPassword"
                                    />
                                </template>
                            </v-text-field>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col>
                            <v-btn
                                block
                                type="submit"
                                color="primary"
                                :text="$t('general.resetPassword')"
                                :disabled="form.processing"
                            />
                        </v-col>
                    </v-row>
                </v-form>
            </v-container>
        </v-main>
    </v-app>
</template>
