<script setup>
import { computed } from "vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";
import PersonTabBar from "@/Components/PersonTabBar.vue";

const { person } = defineProps({
    person: Object,
});

console.dir(person);

const name = computed(() => (person.name.en ? person.name.en : person.name.jp));

const posterUrl = computed(() => {
    if (person?.media && person.media.length > 0) {
        return person.media.filter((m) => m.collection_name === "profile")?.[0]
            .original_url;
    }

    return null;
});

const personEditForm = useForm({
    name: person.name.en,
    original_name: person.name.jp,
    birthdate: person.birthdate,
    career_start: person.career_start,
    career_end: person.career_end,
    country: person.country,
    blood_type: person.blood_type,
    cup_size: person.cup_size,
    height: person.height,
    bust: person.bust,
    waist: person.waist,
    hip: person.hip,
    cup_size: person.cup_size,
    dob_doubt: person.dob_doubt,
});

const submit = () => {
    personEditForm
        .transform((data) => ({
            ...data,
        }))
        .patch(route("models.update", person.id));
};
</script>

<template>
    <AppLayout :title="`${name} - Edit`">
        <div class="col bg-grey-3">
            <PersonTabBar :person="person" />
            <div class="row q-py-md q-px-md">
                <div class="q-pl-none q-mr-lg" style="max-width: 300px">
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
                        <q-icon name="mdi-help" size="60px" color="grey-2" />
                    </div>
                </div>
                <div class="col flex">
                    <div
                        class="column full-height justify-start items-start q-mb-sm"
                    >
                        <h1
                            class="text-h4 q-mt-none q-mb-none ellipsis-2-lines"
                        >
                            {{ name }}
                        </h1>
                        <Link
                            :href="route('models.show', person)"
                            class="text-subtitle1"
                        >
                            <q-icon name="mdi-arrow-left" size="14px" />
                            Back to Overview
                        </Link>
                    </div>
                </div>
            </div>
        </div>
        <div class="q-ma-md">
            <div class="row q-col-gutter-lg full-width">
                <div class="col-2 q-pl-none">
                    <q-card flat bordered>
                        <q-card-section
                            class="bg-primary text-white row items-center"
                        >
                            <div class="text-weight-bold text-h6">Edit</div>
                        </q-card-section>

                        <q-separator />

                        <q-list dense bordered padding>
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
                                    autofocus
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
                                    :error="
                                        !!personEditForm?.errors?.original_name
                                    "
                                    :error-message="
                                        personEditForm.errors.original_name
                                    "
                                />
                            </div>
                        </div>

                        <div class="row q-col-gutter-md">
                            <div class="col">
                                <q-input
                                    filled
                                    v-model="personEditForm.birthdate"
                                    mask="date"
                                    label="Birthdate"
                                >
                                    <template v-slot:append>
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
                                                    v-model="personEditForm.birthdate"
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
                                    :error="
                                        !!personEditForm?.errors?.country
                                    "
                                    :error-message="
                                        personEditForm.errors.country
                                    "
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
                                    filled
                                    v-model="personEditForm.career_start"
                                    mask="date"
                                    label="Career Start"
                                >
                                    <template v-slot:append>
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
                                                    v-model="personEditForm.career_start"
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
                                    filled
                                    v-model="personEditForm.career_end"
                                    mask="date"
                                    label="Career End"
                                >
                                    <template v-slot:append>
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
                                                    v-model="personEditForm.career_end"
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
                                    :error="
                                        !!personEditForm?.errors?.blood_type
                                    "
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
                                    :error="
                                        !!personEditForm?.errors?.cup_size
                                    "
                                    :error-message="
                                        personEditForm.errors.cup_size
                                    "
                                />
                            </div>
                        </div>

                        <div class="row q-col-gutter-md">
                            <div class="col">
                                <q-input
                                    type="number"
                                    id="height"
                                    v-model="personEditForm.height"
                                    label="Height"
                                    filled
                                    :error="
                                        !!personEditForm?.errors?.height
                                    "
                                    :error-message="
                                        personEditForm.errors.height
                                    "
                                >
                                <template v-slot:append>cm</template>
                                </q-input>
                            </div>
                            <div class="col">
                                <q-input
                                    type="number"
                                    id="bust"
                                    v-model="personEditForm.bust"
                                    label="Bust Size"
                                    filled
                                    :error="
                                        !!personEditForm?.errors?.bust
                                    "
                                    :error-message="
                                        personEditForm.errors.bust
                                    "
                                >
                                <template v-slot:append>cm</template>
                                </q-input>
                            </div>
                        </div>

                        <div class="row q-col-gutter-md">
                            <div class="col">
                                <q-input
                                    type="number"
                                    id="waist"
                                    v-model="personEditForm.waist"
                                    label="Waist Size"
                                    filled
                                    :error="
                                        !!personEditForm?.errors?.waist
                                    "
                                    :error-message="
                                        personEditForm.errors.waist
                                    "
                                >
                                <template v-slot:append>cm</template>
                                </q-input>
                            </div>
                            <div class="col">
                                <q-input
                                    type="number"
                                    id="hip"
                                    v-model="personEditForm.hip"
                                    label="Hips Size"
                                    filled
                                    :error="
                                        !!personEditForm?.errors?.hip
                                    "
                                    :error-message="
                                        personEditForm.errors.hip
                                    "
                                >
                                <template v-slot:append>cm</template>
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
    </AppLayout>
</template>
