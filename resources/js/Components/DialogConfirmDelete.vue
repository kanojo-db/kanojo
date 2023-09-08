<script setup lang="ts">
import type { Item, Media } from '@/types/models';
import type { PropType } from 'vue';

import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiClose from '~icons/mdi/close';

import {
    getItemRouteName,
    getItemRouteParams,
    isMedia,
    isMovie,
    isPerson,
    isSeries,
    isStudio,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Item | Media>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const locale = useI18n().locale.value;

const itemName = computed(() => {
    if (isMedia(props.item)) {
        return null;
    }

    if (isMovie(props.item) || isSeries(props.item)) {
        return useTitle(props.item, locale).value;
    }

    if (isPerson(props.item) || isStudio(props.item)) {
        return useName(props.item, locale).value;
    }

    return '';
});

const processing = ref(false);

const confirmDelete = () => {
    processing.value = true;

    if (isMedia(props.item)) {
        router.delete(route('media.destroy', { media: props.item.id }), {
            onSuccess: () => {
                router.reload();
                processing.value = false;
                emit('update:modelValue', false);
            },
        });
    } else {
        router.delete(
            route(
                `${getItemRouteName(props.item)}.destroy`,
                getItemRouteParams(props.item),
            ),
            {
                onSuccess: () => {
                    emit('update:modelValue', false);
                    processing.value = false;
                },
            },
        );
    }
};
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.confirmDelete.title', { itemName: itemName }) }}
            </div>

            <v-spacer />

            <v-btn
                :icon="MdiClose"
                variant="text"
                @click="emit('update:modelValue', false)"
            />
        </v-card-title>

        <v-card-text>
            <p>{{ $t('dialogs.confirmDelete.body') }}</p>
        </v-card-text>

        <v-card-actions>
            <v-btn
                color="error"
                block
                :loading="processing"
                @click="confirmDelete"
            >
                {{ $t('dialogs.confirmDelete.delete') }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>
