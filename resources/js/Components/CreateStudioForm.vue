<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const locale = useI18n().locale;

const form = useForm({
    name: null,
    originalName: null,
    foundedDate: null,
});

const submit = () => {
    form.post(route('studios.store'));
};
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="original_name"
                        text="Original Name"
                    />
                </div>

                <v-text-field
                    v-model="form.originalName"
                    name="original_name"
                    required
                    :error-messages="form.errors.originalName"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="translated_name"
                        :text="`Translated Name (${locale})`"
                    />
                </div>

                <v-text-field
                    v-model="form.name"
                    name="translated_name"
                    :error-messages="form.errors.name"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="founded_date"
                        text="Founded Date"
                    />
                </div>

                <v-text-field
                    v-model="form.foundedDate"
                    name="founded_date"
                    type="date"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.foundedDate"
                />
            </v-col>

            <v-col />

            <v-col />
        </v-row>

        <v-row>
            <v-col>
                <v-btn
                    color="primary"
                    :text="$t('general.saveChanges')"
                    type="submit"
                    :loading="form.processing"
                />
            </v-col>
        </v-row>
    </v-form>
</template>
