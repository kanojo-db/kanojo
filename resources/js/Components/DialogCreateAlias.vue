<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';

import MdiClose from '~icons/mdi/close';

import { getItemRouteParams } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    person: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const aliasCreateForm = useForm({
    name: '',
});

const submit = () => {
    aliasCreateForm.post(
        route('models.alternative-names.store', {
            ...getItemRouteParams(props.person),
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                aliasCreateForm.reset();

                emit('update:modelValue', false);
            },
        },
    );
};
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.create_alias.title') }}
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
                <v-label for="alias-name">
                    {{ $t('dialogs.create_alias.name') }}
                </v-label>

                <v-text-field
                    v-model="aliasCreateForm.name"
                    name="alias-name"
                    required
                />
            </v-card-text>

            <v-card-actions>
                <v-btn
                    type="submit"
                    color="primary"
                    block
                    :text="$t('general.saveChanges')"
                    :loading="aliasCreateForm.processing"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
