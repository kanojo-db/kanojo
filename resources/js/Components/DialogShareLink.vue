<script setup lang="ts">
import copy from 'copy-to-clipboard';
import { ref } from 'vue';

import MdiClose from '~icons/mdi/close';
import MdiContentCopy from '~icons/mdi/content-copy';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    url: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const url = ref(props.url);
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.shareLink.title', { pageName: title }) }}
            </div>

            <v-spacer />

            <v-btn
                :icon="MdiClose"
                variant="text"
                @click="emit('update:modelValue', false)"
            />
        </v-card-title>

        <v-card-text>
            <div class="mb-2 font-bold">
                {{ $t('dialogs.shareLink.url') }}
            </div>

            <v-text-field
                v-model="url"
                class="mb-2"
                readonly
                :aria-label="$t('dialogs.shareLink.url')"
                hide-details
            >
                <template #append-inner>
                    <v-btn
                        :icon="MdiContentCopy"
                        :aria-label="$t('general.copy')"
                        variant="text"
                        @click="copy(url)"
                    />
                </template>
            </v-text-field>
        </v-card-text>
    </v-card>
</template>
