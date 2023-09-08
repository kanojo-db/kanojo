<script setup lang="ts">
import type { Series, Studio, Studios } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import useRouteStore from '@/stores/route';
import { getName } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Series>,
        required: true,
    },
    studios: {
        type: Array as PropType<Studios>,
        default: () => [],
    },
});

const locale = useI18n().locale;

const routeStore = useRouteStore();

const currentRoute = computed(() => {
    return routeStore.current;
});

const routeParams = computed(() => {
    return routeStore.params;
});

const studioSearch = ref('');
const studioSearchLoading = ref(false);

watch(studioSearch, (value) => {
    studioSearchLoading.value = true;

    router.get(
        route(currentRoute.value, {
            ...routeParams.value,
            studio: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['studios'],
            onSuccess: () => {
                studioSearchLoading.value = false;
            },
        },
    );
});

const form = useForm({
    title: props.item.title[locale.value] ?? '',
    original_title: props.item.title['ja-JP'] ?? '',
    studio_id: props.item.studio_id ?? null,
});

const submit = () => {
    form.patch(
        route('series.update', {
            series: props.item.slug,
        }),
    );
};
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="original_title"
                        text="Original Title"
                    />
                </div>

                <v-text-field
                    v-model="form.original_title"
                    name="original_title"
                    required
                    :error-messages="form.errors.original_title"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="translated_title"
                        :text="`Translated Name (${locale})`"
                    />
                </div>

                <v-text-field
                    v-model="form.title"
                    name="translated.title"
                    :error-messages="form.errors.title"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="studio"
                        text="Studio"
                    />
                </div>

                <v-autocomplete
                    v-model="form.studio_id"
                    v-model:search="studioSearch"
                    :loading="studioSearchLoading"
                    :items="props.studios"
                    name="studio"
                    item-value="id"
                    :item-title="(item: Studio) => getName(item, locale)"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.studio_id"
                />
            </v-col>

            <v-col />

            <v-col />
        </v-row>

        <v-row>
            <v-col>
                <v-btn
                    color="primary"
                    :text="$t('general.saveChanges')"
                    type="submit"
                    :loading="form.processing"
                />
            </v-col>
        </v-row>
    </v-form>
</template>
