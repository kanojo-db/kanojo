<script setup lang="ts">
import type { Item, Media } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { computed, ref } from 'vue';
import { useDisplay } from 'vuetify';

import MdiThumbsDown from '~icons/mdi/thumbs-down';
import MdiThumbsUp from '~icons/mdi/thumbs-up';

import DialogConfirmDelete from '@/Components/DialogConfirmDelete.vue';
import DialogMediaItem from '@/Components/DialogMediaItem.vue';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    media: {
        type: Object as PropType<Media>,
        required: true,
    },
});

const page = usePage();

const isAdmin = page?.props?.user?.is_administrator;

const likeMediaForm = useForm({});

const likeCount = computed(() => {
    return props.media?.love_reactant?.reactions.filter((reaction) => {
        return reaction.reaction_type_id === 1;
    }).length;
});

const dislikeCount = computed(() => {
    return props.media?.love_reactant?.reactions.filter((reaction) => {
        return reaction.reaction_type_id === 2;
    }).length;
});

const hasLiked = computed(() => {
    const userLike = props.media?.love_reactant?.reactions.filter(
        (reaction) => {
            return reaction.reacter.reacterable.id === page?.props?.user?.id;
        },
    );

    return userLike?.length > 0 && userLike?.[0]?.reaction_type_id === 1;
});

const hasDisliked = computed(() => {
    const userLike = props.media?.love_reactant?.reactions.filter(
        (reaction) => {
            return reaction.reacter.reacterable.id === page?.props?.user?.id;
        },
    );

    return userLike?.length > 0 && userLike?.[0]?.reaction_type_id === 2;
});

const likeMedia = () => {
    likeMediaForm.post(
        route('movies.media.like.store', {
            movie: props.item.slug,
            medium: props.media.id,
        }),
        {
            preserveScroll: true,

            onSuccess: () => {
                likeMediaForm.reset();
                router.reload();
            },
        },
    );
};

const dislikeMedia = () => {
    likeMediaForm.post(
        route('movies.media.dislike.store', {
            movie: props.item.slug,
            medium: props.media.id,
        }),
        {
            preserveScroll: true,

            onSuccess: () => {
                likeMediaForm.reset();
                router.reload();
            },
        },
    );
};

const { thresholds } = useDisplay();

const isMediaDialogVisible = ref(false);
const isDeleteDialogOpen = ref(false);
</script>

<template>
    <v-card class="w-64">
        <v-img
            :src="props.media.original_url"
            class="aspect-[1/1.5] w-64"
            cover
            :alt="props.media.file_name"
        >
            <v-dialog
                v-model="isMediaDialogVisible"
                activator="parent"
                width="auto"
            >
                <dialog-media-item :media="props.media" />
            </v-dialog>

            <div
                class="absolute bottom-0 z-50 flex w-full flex-row justify-center bg-stone-900/40 p-2 text-stone-50"
            >
                <v-btn-group
                    color="primary"
                    divided
                    rounded="xl"
                    variant="flat"
                >
                    <v-btn
                        :color="hasLiked ? 'secondary' : 'primary'"
                        @click.stop="() => likeMedia()"
                    >
                        <template #prepend>
                            <mdi-thumbs-up />
                        </template>
                        {{ likeCount }}
                    </v-btn>

                    <v-btn
                        :color="hasDisliked ? 'secondary' : 'primary'"
                        @click.stop="() => dislikeMedia()"
                    >
                        <template #prepend>
                            <mdi-thumbs-down />
                        </template>
                        {{ dislikeCount }}
                    </v-btn>
                </v-btn-group>
            </div>
        </v-img>

        <v-card-text>
            <div class="flex flex-col">
                <span class="text-lg font-bold">
                    {{ $t('item.media.addedOn') }}
                </span>

                <span>
                    {{
                        DateTime.fromISO(props.media.created_at).toLocaleString(
                            DateTime.DATETIME_MED,
                        )
                    }}
                </span>
            </div>
        </v-card-text>

        <v-card-actions v-if="isAdmin">
            <v-dialog
                v-model="isDeleteDialogOpen"
                :max-width="thresholds.sm"
            >
                <template #activator="{ props: dialogProps }">
                    <v-btn
                        v-bind="dialogProps"
                        color="error"
                        variant="tonal"
                    >
                        {{ $t('general.delete') }}
                    </v-btn>
                </template>

                <dialog-confirm-delete
                    v-model="isDeleteDialogOpen"
                    :item="props.media"
                    :parent-item="props.item"
                />
            </v-dialog>
        </v-card-actions>
    </v-card>
</template>
