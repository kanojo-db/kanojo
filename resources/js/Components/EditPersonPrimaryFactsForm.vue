<script setup lang="ts">
import type { Countries, Genders, Person, Tag } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiLockOpen from '~icons/mdi/lock-open';

import { EditSubroute } from '@/types/enums';
import { getName } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Person>,
        required: true,
    },
    countries: {
        type: Array as PropType<Countries>,
        default: () => [],
    },
    genders: {
        type: Array as PropType<Genders>,
        default: () => [],
    },
});

const locale = useI18n().locale;

const itemEditForm = useForm({
    name: getName(props.item, locale.value, false) ?? '',
    original_name: props.item.name['ja-JP'],
    birthdate: props.item.birthdate,
    country_id: props.item.country?.id,
    gender_id: props.item.gender?.id,
    career_start: props.item.career_start,
    career_end: props.item.career_end,
    blood_type: props.item.blood_type,
    cup_size: props.item.cup_size,
    height: props.item.height,
    bust: props.item.bust,
    waist: props.item.waist,
    hip: props.item.hip,
});

const submit = () => {
    itemEditForm.patch(route('models.update', { model: props.item.slug }));
};
</script>

<template>
    <v-window-item :value="EditSubroute.PrimaryFacts">
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
                        v-model="itemEditForm.original_name"
                        name="original_name"
                        required
                        :error-messages="itemEditForm.errors.original_name"
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
                        v-model="itemEditForm.name"
                        name="translated_name"
                        :error-messages="itemEditForm.errors.name"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="birthdate"
                            text="Birthdate"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.birthdate"
                        name="birthdate"
                        type="date"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.birthdate"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="country"
                            text="Country"
                        />
                    </div>

                    <v-autocomplete
                        v-model="itemEditForm.country_id"
                        name="country"
                        :items="props.countries"
                        item-value="id"
                        item-title="name"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.country_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="gender"
                            text="Gender"
                        />
                    </div>

                    <v-select
                        v-model="itemEditForm.gender_id"
                        name="gender"
                        :items="props.genders"
                        item-value="id"
                        :item-title="
                            (item) => getName(item as Tag, 'en-US', false)
                        "
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.gender_id"
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
                        v-model="itemEditForm.career_start"
                        name="career_start"
                        type="date"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.career_start"
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
                        v-model="itemEditForm.career_end"
                        name="career_end"
                        type="date"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.career_end"
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
                        v-model="itemEditForm.height"
                        name="height"
                        type="number"
                        suffix="cm"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.height"
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
                        v-model="itemEditForm.cup_size"
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
                        :error-messages="itemEditForm.errors.cup_size"
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
                        v-model="itemEditForm.blood_type"
                        name="blood_type"
                        :items="['A', 'B', 'AB', 'O']"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.blood_type"
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
                        v-model="itemEditForm.bust"
                        name="bust"
                        type="number"
                        suffix="cm"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.bust"
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
                        v-model="itemEditForm.waist"
                        name="waist"
                        type="number"
                        suffix="cm"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.waist"
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
                        v-model="itemEditForm.hip"
                        name="hip"
                        type="number"
                        suffix="cm"
                        clearable
                        persistent-clear
                        :error-messages="itemEditForm.errors.hip"
                    />
                </v-col>
            </v-row>

            <v-row>
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
