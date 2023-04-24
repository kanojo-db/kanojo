<script setup lang="ts">
import type { Countries, Genders } from '@/types/models';

import { useForm, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import { getName } from '@/utils/item';

const page = usePage();

const countries = computed(() => {
    return page.props.country as Countries;
});

const genders = computed(() => {
    return page.props.gender as Genders;
});

const locale = useI18n().locale;

const form = useForm({
    name: null,
    original_name: null,
    birthdate: null,
    country_id: null,
    gender_id: null,
    career_start: null,
    career_end: null,
    blood_type: null,
    cup_size: null,
    height: null,
    bust: null,
    waist: null,
    hip: null,
});

const submit = () => {
    form.post(route('models.store'));
};
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-row>
            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="original_name"
                        text="Original Name"
                    />
                </div>

                <v-text-field
                    v-model="form.original_name"
                    name="original_name"
                    required
                    :error-messages="form.errors.original_name"
                />
            </v-col>

            <v-col>
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="translated_name"
                        :text="`Translated Name (${locale})`"
                    />
                </div>

                <v-text-field
                    v-model="form.name"
                    name="translated_name"
                    :error-messages="form.errors.name"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="birthdate"
                        text="Birthdate"
                    />
                </div>

                <v-text-field
                    v-model="form.birthdate"
                    name="birthdate"
                    type="date"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.birthdate"
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="country"
                        text="Country"
                    />
                </div>

                <v-autocomplete
                    v-model="form.country_id"
                    :items="countries"
                    name="country"
                    item-value="id"
                    item-title="name"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.country_id"
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <v-label
                        for="gender"
                        text="Gender"
                    />
                </div>

                <v-autocomplete
                    v-model="form.gender_id"
                    :items="genders"
                    name="gender"
                    item-value="id"
                    :item-title="(gender: Gender) => getName(gender, locale)"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.gender_id"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="career_start"
                        text="Debut Date"
                    />
                </div>

                <v-text-field
                    v-model="form.career_start"
                    name="career_start"
                    type="date"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.career_start"
                    hint="Ususally the release of their first movie."
                    persistent-hint
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="career_end"
                        text="Retirement Date"
                    />
                </div>

                <v-text-field
                    v-model="form.career_end"
                    name="career_end"
                    type="date"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.career_end"
                    hint="Usually the release of their last movie, unless announced officially."
                    persistent-hint
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="height"
                        text="Height"
                    />
                </div>

                <v-text-field
                    v-model="form.height"
                    name="height"
                    type="number"
                    suffix="cm"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.height"
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="cup_size"
                        text="Cup Size"
                    />
                </div>

                <v-select
                    v-model="form.cup_size"
                    name="cup_size"
                    :items="[
                        'A',
                        'B',
                        'C',
                        'D',
                        'E',
                        'F',
                        'G',
                        'H',
                        'I',
                        'J',
                        'K',
                        'L',
                        'M',
                        'N',
                        'O',
                        'P',
                        'Q',
                        'R',
                        'S',
                        'T',
                        'U',
                        'V',
                        'W',
                        'X',
                        'Y',
                        'Z',
                    ]"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.cup_size"
                    hint="Use the Japanese sizing system."
                    persistent-hint
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="blood_type"
                        text="Blood Type"
                    />
                </div>

                <v-select
                    v-model="form.blood_type"
                    name="blood_type"
                    :items="['A', 'B', 'AB', 'O']"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.blood_type"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="bust"
                        text="Bust Size"
                    />
                </div>

                <v-text-field
                    v-model="form.bust"
                    name="bust"
                    type="number"
                    suffix="cm"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.bust"
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="waist"
                        text="Waist Size"
                    />
                </div>

                <v-text-field
                    v-model="form.waist"
                    name="waist"
                    type="number"
                    suffix="cm"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.waist"
                />
            </v-col>

            <v-col class="pt-0">
                <div class="mb-2 flex flex-row items-center">
                    <mdi-lock-open class="mr-2 text-stone-500" />

                    <v-label
                        for="hip"
                        text="Hip Size"
                    />
                </div>

                <v-text-field
                    v-model="form.hip"
                    name="hip"
                    type="number"
                    suffix="cm"
                    clearable
                    persistent-clear
                    :error-messages="form.errors.hip"
                />
            </v-col>
        </v-row>

        <v-row>
            <v-col>
                <v-btn
                    color="primary"
                    text="Add"
                    type="submit"
                    :loading="form.processing"
                />
            </v-col>
        </v-row>
    </v-form>
</template>
