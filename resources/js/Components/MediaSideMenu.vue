<script setup lang="ts">
import type { Movie, Person } from '@/types/models';
import type { PropType } from 'vue';
import type { RouteParams } from 'ziggy-js';

import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDisplay } from 'vuetify';

import MdiPlusCircle from '~icons/mdi/plus-circle';

import DialogMediaUpload from '@/Components/DialogMediaUpload.vue';
import SideMenu from '@/Components/SideMenu.vue';
import { MediaCollection } from '@/types/enums';
import {
    getItemRouteName,
    getItemRouteParams,
    isMovie,
    isPerson,
    isSeries,
    isStudio,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Movie | Person>,
        required: true,
    },
    currentCollectionName: {
        type: String as PropType<MediaCollection>,
        required: true,
    },
});

const mediaCollectionCount = (collectionName: string) => {
    return props.item.media.filter((media) => {
        return media.collection_name === collectionName;
    }).length;
};

const validMediaTypes = computed(() => {
    if (isMovie(props.item)) {
        return [MediaCollection.FrontCover, MediaCollection.FullCover];
    }

    if (isPerson(props.item)) {
        return [MediaCollection.Profile];
    }

    if (isStudio(props.item)) {
        return [MediaCollection.Logo];
    }

    if (isSeries(props.item)) {
        return [MediaCollection.FrontCover];
    }

    return [];
});

const routeName = computed(() => {
    return getItemRouteName(props.item);
});

const routeParam = computed<RouteParams>(() => {
    return getItemRouteParams(props.item);
});

const isUploadDialogOpen = ref(false);

const { mdAndUp, thresholds } = useDisplay();

const updateCurrentTab = (type: string) => {
    router.get(
        route(`${routeName.value}.media.index`, {
            ...routeParam.value,
            type,
        }),
        {},
        {
            preserveState: true,
            only: ['images', 'mediaCollectionCount'],
        },
    );
};
</script>

<template>
    <side-menu>
        <template #header>
            <div class="text-lg font-bold">
                {{ $t('media.title') }}
            </div>

            <v-spacer />

            <v-dialog
                v-model="isUploadDialogOpen"
                :fullscreen="!mdAndUp"
                :max-width="mdAndUp ? thresholds.sm : undefined"
            >
                <template #activator="{ props: dialogProps }">
                    <v-btn
                        v-bind="dialogProps"
                        :icon="MdiPlusCircle"
                        variant="text"
                    />
                </template>

                <dialog-media-upload
                    v-model="isUploadDialogOpen"
                    :item="props.item"
                    :current-collection-name="props.currentCollectionName"
                />
            </v-dialog>
        </template>

        <template #default>
            <v-list-item
                v-for="collectionType in validMediaTypes"
                :key="collectionType"
                :active="collectionType === props.currentCollectionName"
                @click="() => updateCurrentTab(collectionType)"
            >
                <span>
                    {{ $t(`media.types.${collectionType}`) }}
                </span>

                <template #append>
                    <v-chip>
                        {{ mediaCollectionCount(collectionType) }}
                    </v-chip>
                </template>
            </v-list-item>
        </template>
    </side-menu>
</template>
