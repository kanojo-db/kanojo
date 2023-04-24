<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

import MdiClose from '~icons/mdi/close';

defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const tokenCreateForm = useForm({
    name: '',
});

const submit = () => {
    tokenCreateForm.post(route('settings.tokens.create'), {
        preserveScroll: true,
        onSuccess: () => {
            tokenCreateForm.reset();

            emit('update:modelValue', false);
        },
    });
};
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.create_token.title') }}
            </div>

            <v-spacer />

            <v-btn
                :icon="MdiClose"
                variant="text"
                @click="emit('update:modelValue', false)"
            />
        </v-card-title>

        <v-divider />

        <v-form @submit.prevent="submit">
            <v-card-text>
                <v-label for="token-name">
                    {{ $t('dialogs.create_token.description') }}
                </v-label>

                <v-text-field
                    v-model="tokenCreateForm.name"
                    name="token-name"
                    required
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <v-btn
                    type="submit"
                    color="primary"
                    :text="$t('general.saveChanges')"
                    :loading="tokenCreateForm.processing"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
