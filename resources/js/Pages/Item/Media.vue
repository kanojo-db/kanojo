<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import ItemInfoHeader from '@/Components/ItemInfoHeader.vue';
import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import MediaCard from '@/Components/MediaCard.vue';
import MediaSideMenu from '@/Components/MediaSideMenu.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import useRouteStore from '@/stores/route';
import { MediaCollection } from '@/types/enums';
import {
    isMovie,
    isPerson,
    isSeries,
    isStudio,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    images: {
        type: Object,
        required: true,
    },
    mediaCollectionCount: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const { locale } = useI18n();

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const routeStore = useRouteStore();

const defaultCollectionName = computed<MediaCollection>(() => {
    let defaultCollectionName = MediaCollection.FrontCover;

    if (isMovie(props.item)) {
        defaultCollectionName = MediaCollection.FrontCover;
    }

    if (isPerson(props.item)) {
        defaultCollectionName = MediaCollection.Profile;
    }

    if (isStudio(props.item)) {
        defaultCollectionName = MediaCollection.Logo;
    }

    if (isSeries(props.item)) {
        defaultCollectionName = MediaCollection.FrontCover;
    }

    return defaultCollectionName;
});

const currentCollectionName = computed(
    () => routeStore.params?.type ?? defaultCollectionName,
);
</script>

<template>
    <Head :title="`Media for ${title}`" />

    <item-info-page :item="props.item">
        <template #header>
            <item-info-header :item="item" />
        </template>

        <template #default>
            <side-menu-page>
                <template #left>
                    <media-side-menu
                        :item="item"
                        :current-collection-name="currentCollectionName"
                    />
                </template>

                <template #right>
                    <div v-if="images.length === 0">
                        {{ $t('item.media.no_media') }}
                    </div>

                    <div
                        v-else
                        class="grid grow grid-cols-auto-fill-256 gap-4"
                    >
                        <media-card
                            v-for="image in images"
                            :key="image.id"
                            :media="image"
                            :item="props.item"
                        />
                    </div>
                </template>
            </side-menu-page>
        </template>
    </item-info-page>
</template>
