<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDisplay } from 'vuetify';

import MdiPlusCircle from '~icons/mdi/plus-circle';

import DialogMediaUpload from '@/Components/DialogMediaUpload.vue';
import SideMenu from '@/Components/SideMenu.vue';
import useRouteStore from '@/stores/route';
import { MediaCollection } from '@/types/enums';
import { isMovie, isPerson, isSeries, isStudio } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

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

function getDefaultTab(item: Item) {
    if (isMovie(item)) {
        return MediaCollection.FrontCover;
    }

    if (isPerson(item)) {
        return MediaCollection.Profile;
    }

    if (isStudio(item)) {
        return MediaCollection.Logo;
    }

    if (isSeries(item)) {
        return MediaCollection.FrontCover;
    }

    return MediaCollection.FrontCover;
}

const routeStore = useRouteStore();

const routeName = computed(() => {
    return routeStore.current;
});

const routeParam = computed(() => {
    return routeStore.params;
});

const isUploadDialogOpen = ref(false);

const { mdAndUp, thresholds } = useDisplay();

const currentTab = computed<MediaCollection>(() => {
    return routeStore.params.type ?? getDefaultTab(props.item);
});

function getCollectionCount(collectionType: MediaCollection) {
    switch (collectionType) {
        case MediaCollection.FrontCover:
            if (isMovie(props.item)) {
                return props.item.poster_count;
            }
            break;
        case MediaCollection.FullCover:
            if (isMovie(props.item)) {
                return props.item.fanart_count;
            }
            break;
        case MediaCollection.Logo:
            if (isStudio(props.item)) {
                return props.item.logo_count;
            }
            break;
        case MediaCollection.Profile:
            if (isPerson(props.item)) {
                return props.item.poster_count;
            }
            break;
    }

    return 0;
}

const updateCurrentTab = (type: MediaCollection) => {
    router.get(
        route(routeName.value, {
            ...routeParam.value,
            type,
        }),
        {},
        {
            preserveState: true,
            only: ['images'],
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
                    :current-collection-name="currentTab"
                />
            </v-dialog>
        </template>

        <template #default>
            <v-item-group
                :model-value="currentTab"
                mandatory
                @update:model-value="updateCurrentTab"
            >
                <v-item
                    v-for="collectionType in validMediaTypes"
                    :key="collectionType"
                    v-slot="{ isSelected, toggle }"
                    :value="collectionType"
                >
                    <v-list-item
                        :active="isSelected"
                        @click="toggle"
                    >
                        <span>
                            {{ $t(`media.types.${collectionType}`) }}
                        </span>

                        <template #append>
                            <v-chip>
                                {{ getCollectionCount(collectionType) }}
                            </v-chip>
                        </template>
                    </v-list-item>
                </v-item>
            </v-item-group>
        </template>
    </side-menu>
</template>
