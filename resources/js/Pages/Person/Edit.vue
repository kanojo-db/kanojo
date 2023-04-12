<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { PropType } from 'vue';
import { useI18n } from 'vue-i18n';

import PersonTabBar from '@/Components/PersonTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Person } from '@/types/models';
import { useName } from '@/utils/item';
import { useFirstImage } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    person: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.person, locale);

const posterUrl = useFirstImage(props.person, 'profile');

const personEditForm = useForm({
    name: props.person.name[locale],
    original_name: props.person.name['ja-JP'],
    birthdate: props.person.birthdate,
    career_start: props.person.career_start,
    career_end: props.person.career_end,
    country: props.person.country,
    blood_type: props.person.blood_type,
    cup_size: props.person.cup_size,
    height: props.person.height,
    bust: props.person.bust,
    waist: props.person.waist,
    hip: props.person.hip,
    dob_doubt: props.person.dob_doubt,
});

const submit = () => {
    personEditForm
        .transform((data) => ({
            ...data,
        }))
        .patch(route('models.update', props.person.id));
};
</script>

<template>
    <div class="col bg-grey-3">
        <PersonTabBar :person="person" />
        <div class="row q-py-md q-px-md">
            <div
                class="q-pl-none q-mr-lg"
                style="max-width: 300px"
            >
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="80px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center"
                    style="width: 80px; height: 120px"
                >
                    <q-icon
                        name="mdi-help"
                        size="60px"
                        color="grey-2"
                    />
                </div>
            </div>
            <div class="col flex">
                <div
                    class="column full-height justify-start items-start q-mb-sm"
                >
                    <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                        {{ name }}
                    </h1>
                    <Link
                        :href="route('models.show', person)"
                        class="text-subtitle1"
                    >
                        <q-icon
                            name="mdi-arrow-left"
                            size="14px"
                        />
                        Back to Overview
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <div class="q-ma-md">
        <div class="row q-col-gutter-lg full-width">
            <div class="col-2 q-pl-none">
                <q-card
                    flat
                    bordered
                >
                    <q-card-section
                        class="bg-primary text-white row items-center"
                    >
                        <div class="text-weight-bold text-h6">Edit</div>
                    </q-card-section>

                    <q-separator />

                    <q-list
                        dense
                        bordered
                        padding
                    >
                        <q-item clickable>
                            <q-item-section>General</q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-10">
                <q-form @submit.prevent="submit">
                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="name"
                                v-model="personEditForm.name"
                                label="Name"
                                filled
                                :error="!!personEditForm?.errors?.name"
                                :error-message="personEditForm.errors.name"
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="original_name"
                                v-model="personEditForm.original_name"
                                label="Original Name"
                                filled
                                required
                                :error="!!personEditForm?.errors?.original_name"
                                :error-message="
                                    personEditForm.errors.original_name
                                "
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                v-model="personEditForm.birthdate"
                                filled
                                mask="date"
                                label="Birthdate"
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
                                            <q-date
                                                v-model="
                                                    personEditForm.birthdate
                                                "
                                            >
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
                                id="country"
                                v-model="personEditForm.country"
                                label="Country"
                                filled
                                :error="!!personEditForm?.errors?.country"
                                :error-message="personEditForm.errors.country"
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md q-mb-md">
                        <q-checkbox
                            v-model="personEditForm.dob_doubt"
                            label="The birthdate is a guess or lacks evidence"
                            color="primary"
                            :true-value="1"
                            :false-value="0"
                        />
                    </div>

                    <div class="row q-col-gutter-md q-mb-md">
                        <div class="col">
                            <q-input
                                v-model="personEditForm.career_start"
                                filled
                                mask="date"
                                label="Career Start"
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
                                            <q-date
                                                v-model="
                                                    personEditForm.career_start
                                                "
                                            >
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
                                v-model="personEditForm.career_end"
                                filled
                                mask="date"
                                label="Career End"
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
                                            <q-date
                                                v-model="
                                                    personEditForm.career_end
                                                "
                                            >
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
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="blood_type"
                                v-model="personEditForm.blood_type"
                                label="Blood Type"
                                filled
                                :error="!!personEditForm?.errors?.blood_type"
                                :error-message="
                                    personEditForm.errors.blood_type
                                "
                            />
                        </div>
                        <div class="col">
                            <q-input
                                id="cup_size"
                                v-model="personEditForm.cup_size"
                                label="Cup Size"
                                filled
                                :error="!!personEditForm?.errors?.cup_size"
                                :error-message="personEditForm.errors.cup_size"
                            />
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="height"
                                v-model="personEditForm.height"
                                type="number"
                                label="Height"
                                filled
                                :error="!!personEditForm?.errors?.height"
                                :error-message="personEditForm.errors.height"
                            >
                                <template #append> cm </template>
                            </q-input>
                        </div>
                        <div class="col">
                            <q-input
                                id="bust"
                                v-model="personEditForm.bust"
                                type="number"
                                label="Bust Size"
                                filled
                                :error="!!personEditForm?.errors?.bust"
                                :error-message="personEditForm.errors.bust"
                            >
                                <template #append> cm </template>
                            </q-input>
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-input
                                id="waist"
                                v-model="personEditForm.waist"
                                type="number"
                                label="Waist Size"
                                filled
                                :error="!!personEditForm?.errors?.waist"
                                :error-message="personEditForm.errors.waist"
                            >
                                <template #append> cm </template>
                            </q-input>
                        </div>
                        <div class="col">
                            <q-input
                                id="hip"
                                v-model="personEditForm.hip"
                                type="number"
                                label="Hips Size"
                                filled
                                :error="!!personEditForm?.errors?.hip"
                                :error-message="personEditForm.errors.hip"
                            >
                                <template #append> cm </template>
                            </q-input>
                        </div>
                    </div>

                    <div class="row q-col-gutter-md">
                        <div class="col">
                            <q-btn
                                color="primary"
                                label="Edit"
                                type="submit"
                                :disabled="personEditForm.processing"
                            />
                        </div>
                    </div>
                </q-form>
            </div>
        </div>
    </div>
</template>
