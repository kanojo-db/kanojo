<script setup lang="ts">
import type {
    Countries,
    Genders,
    Item,
    MovieTypes,
    Series,
    Studios,
} from '@/types/models';
import type { PropType } from 'vue';

import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import EditMovieCast from '@/Components/EditMovieCast.vue';
import EditMovieExternalLinksForm from '@/Components/EditMovieExternalLinksForm.vue';
import EditMoviePrimaryFactsForm from '@/Components/EditMoviePrimaryFactsForm.vue';
import EditMovieVersion from '@/Components/EditMovieVersion.vue';
import EditPersonAliases from '@/Components/EditPersonAliases.vue';
import EditPersonExternalLinksForm from '@/Components/EditPersonExternalLinksForm.vue';
import EditPersonPrimaryFactsForm from '@/Components/EditPersonPrimaryFactsForm.vue';
import EditSeriesPrimaryFactsForm from '@/Components/EditSeriesPrimaryFactsForm.vue';
import EditSideMenu from '@/Components/EditSideMenu.vue';
import EditStudioExternalLinksForm from '@/Components/EditStudioExternalLinksForm.vue';
import EditStudioPrimaryFactsForm from '@/Components/EditStudioPrimaryFactsForm.vue';
import ItemInfoHeader from '@/Components/ItemInfoHeader.vue';
import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import useRouteStore from '@/stores/route';
import { EditSubroute } from '@/types/enums';
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
    movieTypes: {
        type: Array as PropType<MovieTypes>,
        default: () => [],
    },
    studios: {
        type: Array as PropType<Studios>,
        default: () => [],
    },
    series: {
        type: Array as PropType<Series[]>,
        default: () => [],
    },
    countries: {
        type: Array as PropType<Countries>,
        default: () => [],
    },
    genders: {
        type: Array as PropType<Genders>,
        default: () => [],
    },
});

defineOptions({
    layout: AppLayout,
});

const locale = useI18n().locale;

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const routeStore = useRouteStore();

const currentTab = ref(
    // eslint-disable-next-line vue/no-ref-object-destructure -- We just want the value, reactivity is handled by updateCurrentTab.
    routeStore.params?.active_tab ?? 'primary-facts',
);

const updateCurrentTab = (tab: EditSubroute) => {
    currentTab.value = tab;

    router.get(
        route(routeStore.current, {
            ...routeStore.params,
            active_tab: tab,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    );
};
</script>

<template>
    <Head :title="`Edit ${title}`" />

    <item-info-page :item="props.item">
        <template #header>
            <item-info-header :item="item" />
        </template>

        <template #default>
            <side-menu-page>
                <template #left>
                    <edit-side-menu
                        v-model="currentTab"
                        :item="props.item"
                        @update:model-value="updateCurrentTab"
                    />
                </template>

                <template #right>
                    <v-window
                        v-model="currentTab"
                        class="w-full"
                    >
                        <edit-movie-primary-facts-form
                            v-if="isMovie(props.item)"
                            :item="props.item"
                            :movie-types="props.movieTypes"
                            :studios="props.studios"
                            :series="props.series"
                        />

                        <edit-person-primary-facts-form
                            v-if="isPerson(props.item)"
                            :item="props.item"
                            :countries="props.countries"
                            :genders="props.genders"
                        />

                        <edit-studio-primary-facts-form
                            v-if="isStudio(props.item)"
                            :item="props.item"
                        />

                        <edit-series-primary-facts-form
                            v-if="isSeries(props.item)"
                            :item="props.item"
                            :studios="props.studios"
                        />

                        <edit-movie-cast
                            v-if="isMovie(props.item)"
                            :item="props.item"
                        />

                        <edit-person-aliases
                            v-if="isPerson(props.item)"
                            :item="props.item"
                        />

                        <edit-movie-version
                            v-if="isMovie(props.item)"
                            :item="props.item"
                        />

                        <edit-movie-external-links-form
                            v-if="isMovie(props.item)"
                            :item="props.item"
                        />

                        <edit-person-external-links-form
                            v-if="isPerson(props.item)"
                            :item="props.item"
                        />

                        <edit-studio-external-links-form
                            v-if="isStudio(props.item)"
                            :item="props.item"
                        />
                    </v-window>
                </template>
            </side-menu-page>
        </template>
    </item-info-page>
</template>
