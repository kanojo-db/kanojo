<script setup lang="ts">
import type { MediaCollection } from '@/types/enums';
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import MdiClose from '~icons/mdi/close';

import { getItemRouteName, getItemRouteParams } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    currentCollectionName: {
        type: String as PropType<MediaCollection>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const selectedFile = ref<File[] | undefined>(undefined);

const mediaUploadForm = useForm<{
    media: File | undefined;
    collection_name: MediaCollection;
}>({
    media: undefined,
    collection_name: props.currentCollectionName,
});

const submit = () => {
    mediaUploadForm.post(
        route(`${getItemRouteName(props.item)}.media.store`, {
            ...getItemRouteParams(props.item),
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                emit('update:modelValue', false);

                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['images'],
                });
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
                {{ $t('dialogs.media_upload.title') }}
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
                <p>
                    {{ $t('dialogs.media_upload.instructions') }}
                </p>

                <p class="mt-4 font-semibold">
                    {{ $t('dialogs.media_upload.requirements_heading') }}
                </p>

                <ul class="mb-4 ml-4 list-disc">
                    <li>{{ $t('dialogs.media_upload.requirements_1') }}</li>

                    <li>{{ $t('dialogs.media_upload.requirements_2') }}</li>

                    <li>{{ $t('dialogs.media_upload.requirements_3') }}</li>
                </ul>

                <v-file-input
                    v-model="selectedFile"
                    label="Select a file"
                    prepend-icon=""
                    :error-messages="mediaUploadForm.errors.media"
                    @update:model-value="mediaUploadForm.media = $event[0]"
                />
            </v-card-text>

            <v-card-actions>
                <v-btn
                    type="submit"
                    color="primary"
                    block
                    :loading="mediaUploadForm.processing"
                    :text="
                        mediaUploadForm.processing
                            ? `${mediaUploadForm.progress?.percentage}%`
                            : $t('dialogs.media_upload.submit')
                    "
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
