<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const { studios } = defineProps({
    studios: Object,
    movie_types: Object,
    tags: Object,
});

const options = ref(studios);

const studio = ref(null);
const movie_type = ref(null);

const form = useForm({
    title: '',
    original_title: '',
    product_code: '',
    release_date: new Date(0),
    length: 0,
    poster: null,
    tags: null,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        studio_id: studio.value?.id,
        movie_type_id: movie_type.value?.id,
        tags: data.tags.map((v) => v.name.en),
    })).post(route('movies.store'));
};

const filterFn = (val, update, abort) => {
    update(() => {
        const needle = val.toLowerCase();
        options.value = studios.filter(
            (v) => v.name.toLowerCase().indexOf(needle) > -1,
        );
    });
};
</script>

<template>
    <div class="q-pa-md">
        <q-form @submit.prevent="submit">
            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="title"
                        v-model="form.title"
                        label="Title"
                        filled
                        required
                        autofocus
                        :error="!!form?.errors?.title"
                        :error-message="form.errors.title"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="original_title"
                        v-model="form.original_title"
                        label="Original Title"
                        filled
                        required
                        :error="!!form?.errors?.original_title"
                        :error-message="form.errors.original_title"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        id="product_code"
                        v-model="form.product_code"
                        label="Product Code"
                        filled
                        required
                        :error="!!form?.errors?.product_code"
                        :error-message="form.errors.product_code"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-select
                        v-model="studio"
                        clearable
                        filled
                        use-input
                        input-debounce="0"
                        label="Studio"
                        :options="options"
                        option-value="id"
                        option-label="name"
                        :error="!!form?.errors?.studio"
                        :error-message="form.errors.studio"
                        @filter="filterFn"
                    >
                        <template #no-option>
                            <q-item>
                                <q-item-section class="text-grey">
                                    No results
                                </q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </div>
                <div class="col">
                    <q-select
                        v-model="movie_type"
                        clearable
                        filled
                        use-input
                        input-debounce="0"
                        label="Movie Type"
                        :options="movie_types"
                        option-value="id"
                        option-label="name"
                        :error="!!form?.errors?.movie_type"
                        :error-message="form.errors.movie_type"
                        @filter="filterFn"
                    >
                        <template #no-option>
                            <q-item>
                                <q-item-section class="text-grey">
                                    No results
                                </q-item-section>
                            </q-item>
                        </template>
                    </q-select>
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-input
                        v-model="form.release_date"
                        filled
                        mask="date"
                        :rules="['date']"
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
                                    <q-date v-model="form.release_date">
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
                        id="length"
                        v-model="form.length"
                        label="Length"
                        filled
                        required
                        :error="!!form?.errors?.length"
                        :error-message="form.errors.length"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-file
                        v-model="form.poster"
                        filled
                        label="Poster"
                    />
                </div>
            </div>

            <div class="row q-col-gutter-md">
                <div class="col">
                    <q-select
                        v-model="form.tags"
                        filled
                        multiple
                        :options="tags"
                        use-chips
                        label="Tags"
                        :option-value="(option) => option.name.en"
                        :option-label="(option) => option.name.en"
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
    </div>
</template>
