<script setup lang="ts">
import type { Movie, MovieVersion } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiClose from '~icons/mdi/close';

import { useTitle } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
    version: {
        type: Object as PropType<MovieVersion>,
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

const editForm = useForm({
    format: props.version.format,
    product_code: props.version.product_code,
    barcode: props.version.barcode,
    release_date: props.version.release_date,
});

function onOKClick() {
    editForm.patch(
        route('movies.version.update', {
            movie: props.item.slug,
            version: props.version.id,
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                editForm.reset();

                emit('update:modelValue', false);

                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
        },
    );
}
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.editMovieVersion.title', { itemTitle: title }) }}
            </div>

            <v-spacer />

            <v-btn
                :icon="MdiClose"
                variant="text"
                @click="emit('update:modelValue', false)"
            />
        </v-card-title>

        <v-form @submit.prevent="onOKClick">
            <v-card-text>
                <v-label
                    for="format"
                    :text="$t('movie.show.format')"
                />

                <v-select
                    id="format"
                    v-model="editForm.format"
                    :items="versionFormats"
                    item-title="name"
                    item-value="value"
                    required
                    :error-messages="editForm.errors.format"
                />

                <v-label
                    for="product_code"
                    :text="$t('movie.show.product_code')"
                />

                <v-text-field
                    id="product_code"
                    v-model="editForm.product_code"
                    required
                    :error-messages="editForm.errors.product_code"
                />

                <v-label
                    for="release_date"
                    :text="$t('movie.show.release_date')"
                />

                <v-text-field
                    id="release_date"
                    v-model="editForm.release_date"
                    type="date"
                    :error-messages="editForm.errors.release_date"
                />

                <v-label
                    for="barcode"
                    :text="$t('movie.show.barcode')"
                />

                <v-text-field
                    id="barcode"
                    v-model="editForm.barcode"
                    :error-messages="editForm.errors.barcode"
                />
            </v-card-text>

            <v-card-actions class="justify-end">
                <v-btn
                    type="submit"
                    color="primary"
                    block
                    :loading="editForm.processing"
                    :text="$t('general.saveChanges')"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
