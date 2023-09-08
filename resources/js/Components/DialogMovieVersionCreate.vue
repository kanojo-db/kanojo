<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiClose from '~icons/mdi/close';

import { getItemRouteParams, useTitle } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const { locale, t } = useI18n();

const title = useTitle(props.item, locale.value);

const versionFormats = ref([
    {
        name: t('dialogs.editMovieVersion.format.digital'),
        value: 'Digital',
    },
    {
        name: t('dialogs.editMovieVersion.format.dvd'),
        value: 'DVD',
    },
    {
        name: t('dialogs.editMovieVersion.format.bluray'),
        value: 'Blu-ray',
    },
    {
        name: t('dialogs.editMovieVersion.format.uhdBluray'),
        value: 'UHD Blu-ray',
    },
    {
        name: t('dialogs.editMovieVersion.format.vhs'),
        value: 'VHS',
    },
    {
        name: t('dialogs.editMovieVersion.format.laserdisc'),
        value: 'Laserdisc',
    },
    {
        name: t('dialogs.editMovieVersion.format.umd'),
        value: 'UMD',
    },
    {
        name: t('dialogs.editMovieVersion.format.hddvd'),
        value: 'HD DVD',
    },
]);

const versionCreateForm = useForm<{
    format: string;
    product_code: string;
    release_date: string;
    barcode: string;
}>({
    format: '',
    product_code: '',
    release_date: '',
    barcode: '',
});

const submit = () => {
    versionCreateForm.post(
        route(`movies.version.store`, {
            ...getItemRouteParams(props.item),
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                versionCreateForm.reset();

                emit('update:modelValue', false);

                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
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
                {{ $t('dialogs.versionCreate.title', { itemTitle: title }) }}
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
                <v-label
                    for="format"
                    :text="$t('movie.show.format')"
                />

                <v-select
                    id="format"
                    v-model="versionCreateForm.format"
                    :items="versionFormats"
                    item-title="name"
                    item-value="value"
                    required
                    :error-messages="versionCreateForm.errors.format"
                />

                <v-label
                    for="product_code"
                    :text="$t('movie.show.product_code')"
                />

                <v-text-field
                    id="product_code"
                    v-model="versionCreateForm.product_code"
                    required
                    :error-messages="versionCreateForm.errors.product_code"
                />

                <v-label
                    for="release_date"
                    :text="$t('movie.show.release_date')"
                />

                <v-text-field
                    id="release_date"
                    v-model="versionCreateForm.release_date"
                    type="date"
                    :error-messages="versionCreateForm.errors.release_date"
                />

                <v-label
                    for="barcode"
                    :text="$t('movie.show.barcode')"
                />

                <v-text-field
                    id="barcode"
                    v-model="versionCreateForm.barcode"
                    :error-messages="versionCreateForm.errors.barcode"
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <v-btn
                    type="submit"
                    color="primary"
                    :loading="versionCreateForm.processing"
                    :text="$t('general.saveChanges')"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
