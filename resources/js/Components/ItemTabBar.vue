<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { usePage } from '@inertiajs/vue3';
import chroma from 'chroma-js';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDisplay, useTheme } from 'vuetify';

import DialogConfirmDelete from '@/Components/DialogConfirmDelete.vue';
import DialogReportContent from '@/Components/DialogReportContent.vue';
import DialogShareLink from '@/Components/DialogShareLink.vue';
import { MediaCollection } from '@/types/enums';
import {
    isMovie,
    isPerson,
    isSeries,
    isStudio,
    useItemRouteName,
    useItemRouteParams,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    color: {
        type: String,
        required: false,
        default: undefined,
    },
});

const page = usePage();

const locale = useI18n().locale.value;

const headerColor = computed(() => {
    if (props.color) {
        return chroma(props.color).darken(0.3).hex();
    }

    return undefined;
});

const theme = useTheme();

const isTextWhite = computed(() => {
    if (!headerColor.value) {
        if (theme.global.current.value.dark) {
            return true;
        }

        return false;
    }

    const whiteContrast = chroma.contrast(headerColor.value, 'white');
    const blackContrast = chroma.contrast(headerColor.value, 'black');

    return whiteContrast > blackContrast;
});

const title = computed(() => {
    if (isMovie(props.item) || isSeries(props.item)) {
        return useTitle(props.item, locale).value;
    }

    return useName(props.item, locale).value;
});

const fullUrl = ref(
    import.meta.env.SSR ? '' : window.location.href.split('?')[0] ?? '',
);

const isAdmin = page?.props?.user?.is_administrator;

const supportsShareApi = ref(
    !import.meta.env.SSR && navigator && !!navigator.share,
);

const shareWithNavigator = () => {
    if (!supportsShareApi.value) {
        return;
    }

    const shareData = {
        title: title.value ?? undefined,
        url: fullUrl.value,
    };

    if (navigator.canShare(shareData)) {
        navigator.share(shareData);
    } else {
        // If we can't share links, fallback to the dialog
        supportsShareApi.value = false;
    }
};

const routeName = useItemRouteName(props.item);

const routeParam = useItemRouteParams(props.item);

const isReportDialogOpen = ref(false);
const isShareLinkDialogOpen = ref(false);
const isDeleteDialogOpen = ref(false);

const { mdAndUp, thresholds } = useDisplay();

const mediaTypes = computed(() => {
    if (isMovie(props.item)) {
        return [
            {
                key: 'posters',
                route: route(`${routeName.value}.media.index`, {
                    ...routeParam.value,
                    type: MediaCollection.FrontCover,
                }),
                count: props.item.poster_count,
            },
            {
                key: 'fanarts',
                route: route(`${routeName.value}.media.index`, {
                    ...routeParam.value,
                    type: MediaCollection.FullCover,
                }),
                count: props.item.fanart_count,
            },
        ];
    }

    if (isPerson(props.item)) {
        return [
            {
                key: 'profiles',
                route: route(`${routeName.value}.media.index`, {
                    ...routeParam.value,
                    type: MediaCollection.Profile,
                }),
                count: props.item.poster_count,
            },
        ];
    }

    if (isStudio(props.item)) {
        return [
            {
                key: 'logos',
                route: route(`${routeName.value}.media.index`, {
                    ...routeParam.value,
                    type: MediaCollection.Logo,
                }),
                count: props.item.logo_count,
            },
        ];
    }

    return [];
});
</script>

<template>
    <div
        class="flex flex-row gap-2 p-2 md:items-center md:justify-center"
        :class="{
            'bg-stone-400 dark:bg-stone-800': !color,
            'text-stone-50': isTextWhite,
            'text-stone-950': !isTextWhite,
        }"
        :style="{
            'background-color': headerColor,
        }"
    >
        <v-menu open-on-hover>
            <template #activator="{ props: menuProps }">
                <v-btn
                    v-bind="menuProps"
                    variant="text"
                >
                    {{ $t('movie.tabs.overview.title') }}
                </v-btn>
            </template>

            <v-list>
                <v-list-item :to="route(`${routeName}.show`, routeParam)">
                    {{ $t('movie.tabs.overview.main') }}
                </v-list-item>

                <v-divider />

                <v-list-item
                    v-if="!isStudio(props.item) || !isSeries(props.item)"
                    :to="route(`${routeName}.history.index`, routeParam)"
                >
                    {{ $t('movie.tabs.overview.changes') }}
                </v-list-item>

                <v-list-item link>
                    {{ $t('movie.tabs.overview.report') }}

                    <v-dialog
                        v-model="isReportDialogOpen"
                        :fullscreen="!mdAndUp"
                        :max-width="mdAndUp ? thresholds.sm : undefined"
                        activator="parent"
                    >
                        <dialog-report-content
                            v-model="isReportDialogOpen"
                            :item="props.item"
                        />
                    </v-dialog>
                </v-list-item>

                <v-list-item :to="route(`${routeName}.edit`, routeParam)">
                    {{ $t('movie.tabs.overview.edit') }}
                </v-list-item>
            </v-list>
        </v-menu>

        <v-menu open-on-hover>
            <template #activator="{ props: menuProps }">
                <v-btn
                    v-bind="menuProps"
                    variant="text"
                >
                    {{ $t('movie.tabs.media.title') }}
                </v-btn>
            </template>

            <v-list>
                <v-list-item
                    v-for="mediaType in mediaTypes"
                    :key="mediaType.key"
                    :to="mediaType.route"
                >
                    {{ $t(`movie.tabs.media.${mediaType.key}`) }}

                    <template #append>
                        <v-chip
                            v-if="mediaType.count"
                            class="ml-6"
                        >
                            {{ mediaType.count }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-menu open-on-hover>
            <template #activator="{ props: menuProps }">
                <v-btn
                    v-bind="menuProps"
                    variant="text"
                >
                    {{ $t('movie.tabs.community.title') }}
                </v-btn>
            </template>

            <v-list>
                <v-list-item
                    :to="route(`${routeName}.reports.index`, routeParam)"
                >
                    {{ $t('general.pages.contentIssues') }}

                    <template #append>
                        <v-chip
                            v-if="props.item.content_report_count"
                            class="ml-6"
                        >
                            {{ props.item.content_report_count }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-list>
        </v-menu>

        <v-btn
            v-if="supportsShareApi"
            variant="text"
            @click="shareWithNavigator"
        >
            {{ $t('movie.tabs.share.title') }}
        </v-btn>

        <v-btn
            v-else
            variant="text"
        >
            {{ $t('movie.tabs.share.title') }}

            <v-dialog
                v-model="isShareLinkDialogOpen"
                :fullscreen="!mdAndUp"
                :max-width="mdAndUp ? thresholds.sm : undefined"
                activator="parent"
            >
                <dialog-share-link
                    v-model="isShareLinkDialogOpen"
                    :url="fullUrl"
                    :title="title || ''"
                />
            </v-dialog>
        </v-btn>

        <v-menu
            v-if="isAdmin"
            open-on-hover
        >
            <template #activator="{ props: menuProps }">
                <v-btn
                    v-bind="menuProps"
                    variant="text"
                >
                    {{ $t('movie.tabs.manage.title') }}
                </v-btn>
            </template>

            <v-list>
                <v-list-item
                    link
                    class="text-red-500"
                >
                    {{ $t('general.pages.delete') }}

                    <v-dialog
                        v-model="isDeleteDialogOpen"
                        :max-width="thresholds.sm"
                        activator="parent"
                    >
                        <dialog-confirm-delete
                            v-model="isDeleteDialogOpen"
                            :item="props.item"
                        />
                    </v-dialog>
                </v-list-item>
            </v-list>
        </v-menu>
    </div>
</template>
