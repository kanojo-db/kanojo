<script setup lang="ts">
import type {
    Movie,
    MovieTypes,
    Series,
    Studio,
    Studios,
} from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiLockOpen from '~icons/mdi/lock-open';

import useRouteStore from '@/stores/route';
import { EditSubroute } from '@/types/enums';
import { getItemRouteParams, getName, getTitle } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
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
});

const routeStore = useRouteStore();

const locale = useI18n().locale;

const itemEditForm = useForm({
    title: getTitle(props.item, locale.value, false) ?? '',
    original_title: props.item.title['ja-JP'],
    release_date: props.item.release_date,
    runtime: props.item.length,
    type_id: props.item.type.id,
    studio_id: props.item.studio?.id,
    series_id: props.item.series?.id,
    is_vr: props.item.is_vr,
    is_3d: props.item.is_3d,
});

const submit = () => {
    itemEditForm.patch(route('movies.update', getItemRouteParams(props.item)));
};

const studioSearch = ref('');
const studioSearchLoading = ref(false);

watch(studioSearch, (value) => {
    studioSearchLoading.value = true;

    router.get(
        route(routeStore.current, {
            ...routeStore.params,
            studio: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['studios'],
            onFinish: () => {
                studioSearchLoading.value = false;
            },
        },
    );
});

const seriesSearch = ref('');
const seriesSearchLoading = ref(false);

watch(seriesSearch, (value) => {
    seriesSearchLoading.value = true;

    router.get(
        route(routeStore.current, {
            ...routeStore.params,
            series: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['series'],
            onFinish: () => {
                seriesSearchLoading.value = false;
            },
        },
    );
});
</script>

<template>
    <v-window-item :value="EditSubroute.PrimaryFacts">
        <v-form @submit.prevent="submit">
            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="original_title"
                            text="Original Title"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.original_title"
                        name="original_title"
                        required
                        :error-messages="itemEditForm.errors.original_title"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="translated_title"
                            :text="`Translated Title (${locale})`"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.title"
                        name="translated_title"
                        :error-messages="itemEditForm.errors.title"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="type"
                            text="Type"
                        />
                    </div>

                    <v-select
                        v-model="itemEditForm.type_id"
                        name="type"
                        :items="props.movieTypes"
                        item-value="id"
                        item-title="name"
                        :error-messages="itemEditForm.errors.type_id"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="studio"
                            text="Studio"
                        />
                    </div>

                    <v-autocomplete
                        v-model="itemEditForm.studio_id"
                        v-model:search="studioSearch"
                        :loading="studioSearchLoading"
                        :items="props.studios"
                        name="studio"
                        item-value="id"
                        :item-title="(item: Studio) => getName(item, locale)"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.studio_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="series"
                            text="Series"
                        />
                    </div>

                    <v-autocomplete
                        v-model="itemEditForm.series_id"
                        v-model:search="seriesSearch"
                        :loading="seriesSearchLoading"
                        :items="props.series"
                        name="series"
                        item-value="id"
                        :item-title="(item: Series) => getTitle(item, locale)"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.series_id"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="release_date"
                            text="Release Date"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.release_date"
                        type="date"
                        name="release_date"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.release_date"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="runtime"
                            text="Runtime"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.runtime"
                        type="number"
                        name="runtime"
                        suffix="minutes"
                        :error-messages="itemEditForm.errors.runtime"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label text="Flags" />
                    </div>

                    <div class="flex flex-row">
                        <v-checkbox
                            v-model="itemEditForm.is_vr"
                            label="VR"
                            hide-details
                            :error-messages="itemEditForm.errors.is_vr"
                        />

                        <v-checkbox
                            v-model="itemEditForm.is_3d"
                            label="3D"
                            hide-details
                            :error-messages="itemEditForm.errors.is_3d"
                        />
                    </div>
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <v-btn
                        type="submit"
                        color="primary"
                        :loading="itemEditForm.processing"
                    >
                        {{ $t('general.saveChanges') }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-window-item>
</template>
