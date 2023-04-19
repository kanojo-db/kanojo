<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { PropType, ref } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import { MovieType, MovieTypes, Studio, Studios, Tags } from '@/types/models';
import { getName } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    studios: {
        type: Array as PropType<Studios>,
        required: true,
    },
    movieTypes: {
        type: Array as PropType<MovieTypes>,
        required: true,
    },
    tags: {
        type: Array as PropType<Tags>,
        required: true,
    },
});

const options = ref(props.studios);

const studio = ref<Studio>();
const movie_type = ref<MovieType>();

const form = useForm({
    title: '',
    originalTitle: '',
    productCode: '',
    releaseDate: null,
    runtime: 0,
    tags: [],
    studioId: null,
    movieTypeId: null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        studioId: studio.value?.id,
        movieTypeId: movie_type.value?.id,
        tags: data.tags.map((v: any) => v?.name.id),
    })).post(route('movies.store'));
};

const filterFn = (val: string, update: any) => {
    update(() => {
        const needle = val.toLowerCase();
        options.value = props.studios.filter((v) => {
            const name = getName(v, 'en-US');

            return name.toLowerCase().indexOf(needle) > -1;
        });
    });
};
</script>

<template>
    <div class="col bg-grey-3">
        <div class="column q-py-lg q-px-md">
            <h1 class="text-h4 q-mt-none q-mb-md">Add a movie</h1>
            <q-card
                flat
                class="bg-grey-4 q-pa-md"
                style="max-width: 600px"
            >
                <p class="text-body1 text-weight-bolder q-mb-sm">
                    Thank you for wanting to help!
                </p>
                <p class="text-body1 q-mb-sm">
                    Before you continue, make sure the title you are adding is
                    not already in the database. Search for the product code or
                    title using the search button in the top right corner.
                </p>
                <p class="text-body1 q-mb-none">
                    Make sure to read the
                    <Link
                        class="underline"
                        :href="route('bible.general')"
                    >
                        Contribution Bible
                    </Link>
                    before you start adding a movie.
                </p>
            </q-card>
        </div>
    </div>
    <div class="row q-pa-md q-gutter-md">
        <q-form
            class="col"
            @submit.prevent="submit"
        >
            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="product_code"
                        v-model="form.productCode"
                        label="Product Code *"
                        stack-label
                        required
                        :error="!!form?.errors?.productCode"
                        :error-message="form.errors.productCode"
                    />
                </div>

                <div class="col">
                    <q-select
                        v-model="movie_type"
                        clearable
                        stack-label
                        use-input
                        input-debounce="0"
                        label="Movie Type *"
                        :options="movieTypes"
                        option-value="id"
                        option-label="name"
                        :error="!!form?.errors?.movieTypeId"
                        :error-message="form.errors.movieTypeId"
                        @filter="filterFn"
                    >
                        <template #no-option>
                            <q-item>
                                <q-item-section class="text-grey">
                                    {{ $t('web.search.no_results') }}
                                </q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="title"
                        v-model="form.title"
                        label="Title"
                        stack-label
                        :error="!!form?.errors?.title"
                        :error-message="form.errors.title"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="original_title"
                        v-model="form.originalTitle"
                        label="Japanese Title *"
                        stack-label
                        required
                        :error="!!form?.errors?.originalTitle"
                        :error-message="form.errors.originalTitle"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-select
                        v-model="studio"
                        clearable
                        stack-label
                        use-input
                        input-debounce="0"
                        label="Studio"
                        :options="options"
                        option-value="id"
                        :error="!!form?.errors?.studioId"
                        :error-message="form.errors.studioId"
                        @filter="filterFn"
                    >
                        <template #selected>
                            {{ studio?.name ? getName(studio, 'en-US') : '' }}
                        </template>
                        <template #option="scope">
                            <q-item v-bind="scope.itemProps">
                                <q-item-section>
                                    <q-item-label>
                                        {{
                                            scope.opt.name
                                                ? getName(scope.opt, 'en-US')
                                                : ''
                                        }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                        </template>
                        <template #no-option>
                            <q-item>
                                <q-item-section class="text-grey">
                                    {{ $t('web.search.no_results') }}
                                </q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </div>
            </div>

            <div class="col-6 row q-col-gutter-md">
                <div class="col">
                    <q-input
                        v-model="form.releaseDate"
                        clearable
                        stack-label
                        mask="date"
                        label="Release Date"
                    >
                        <template #append>
                            <q-icon
                                name="mdi-calendar"
                                class="cursor-pointer"
                            >
                                <q-popup-proxy
                                    cover
                                    transition-show="scale"
                                    transition-hide="scale"
                                >
                                    <q-date v-model="form.releaseDate">
                                        <div
                                            class="row items-center justify-end"
                                        >
                                            <q-btn
                                                v-close-popup
                                                label="Close"
                                                color="primary"
                                                flat
                                            />
                                        </div>
                                    </q-date>
                                </q-popup-proxy>
                            </q-icon>
                        </template>
                    </q-input>
                </div>
                <div class="col">
                    <q-input
                        id="runtime"
                        v-model="form.runtime"
                        label="Length"
                        stack-label
                        required
                        :error="!!form?.errors?.runtime"
                        :error-message="form.errors.runtime"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-btn
                        color="primary"
                        label="Add"
                        type="submit"
                        :disabled="form.processing"
                    />
                </div>
            </div>
        </q-form>
        <q-card
            flat
            class="col bg-grey-1 q-pa-md"
            style="max-width: 600px"
        >
            <p class="text-overline text-grey-6">
                * indicates required fields.
            </p>
            <p class="text-h6">What is a product code?</p>
            <p class="text-body1">
                A movie is mainly identified by its <b>product code</b>. The
                product code is a mostly unique identifier for a movie. It is
                usually a combination of a short name for the studio or a
                series, and a number.
            </p>
            <p class="text-body1">
                The
                <Link
                    class="underline"
                    :href="route('bible.general')"
                >
                    Contribution Bible
                </Link>
                contains a list of special product codes that are used for
                movies from websites, magazines, and other special sources.
            </p>
            <p class="text-body1">
                While we strive to be as complete as possible, some titles may
                be banned due to their content. If you are unsure whether a
                title is banned or not, please take a look at the
                <Link
                    class="underline"
                    :href="route('bible.general')"
                >
                    list of banned titles in the Contribution Bible
                </Link>
                .
            </p>
        </q-card>
    </div>
</template>
