<script setup lang="ts">
import type { PropType } from 'vue';

import { Head } from '@inertiajs/vue3';

import CreateMovieForm from '@/Components/CreateMovieForm.vue';
import CreatePersonForm from '@/Components/CreatePersonForm.vue';
import CreateSeriesForm from '@/Components/CreateSeriesForm.vue';
import CreateStudioForm from '@/Components/CreateStudioForm.vue';
import ItemCreatePage from '@/Components/ItemCreatePage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    type: {
        type: String as PropType<'movie' | 'person' | 'studio' | 'series'>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head :title="$t(`general.pages.create.${props.type}`)" />

    <item-create-page>
        <template #default>
            <v-container>
                <create-movie-form v-if="props.type === 'movie'" />

                <create-person-form v-if="props.type === 'person'" />

                <create-studio-form v-if="props.type === 'studio'" />

                <create-series-form v-if="props.type === 'series'" />
            </v-container>
        </template>
    </item-create-page>
</template>
