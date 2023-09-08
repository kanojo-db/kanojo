<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';

import MdiCheckboxBlankOutline from '~icons/mdi/checkbox-blank-outline';
import MdiCheckboxMarkedOutline from '~icons/mdi/checkbox-marked-outline';
import MdiClose from '~icons/mdi/close';
import MdiRadioboxBlank from '~icons/mdi/radiobox-blank';
import MdiRadioBoxMarked from '~icons/mdi/radiobox-marked';

import { getItemRouteName, getItemRouteParams } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const reportForm = useForm({
    type: null,
    message: '',
    public: true,
});

function onOKClick() {
    reportForm.post(
        route(`${getItemRouteName(props.item)}.reports.store`, {
            ...getItemRouteParams(props.item),
        }),
        {
            onSuccess: () => {
                emit('update:modelValue', false);
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
                {{ $t('dialogs.reportContent.title') }}
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
                <h2 class="mb-6 text-lg font-bold">
                    {{ $t('dialogs.reportContent.type_of_problem') }}
                </h2>

                <v-radio-group
                    v-model="reportForm.type"
                    :error-messages="reportForm.errors.type"
                >
                    <v-row>
                        <v-col
                            class="p-0"
                            cols="12"
                            md="6"
                        >
                            <v-radio
                                :true-icon="MdiRadioBoxMarked"
                                :false-icon="MdiRadioboxBlank"
                                value="duplicate"
                                :label="$t('dialogs.reportContent.duplicate')"
                            />
                        </v-col>

                        <v-col
                            class="p-0"
                            cols="12"
                            md="6"
                        >
                            <v-radio
                                :true-icon="MdiRadioBoxMarked"
                                :false-icon="MdiRadioboxBlank"
                                value="incorrect"
                                :label="$t('dialogs.reportContent.incorrect')"
                            />
                        </v-col>

                        <v-col
                            class="p-0"
                            cols="12"
                            md="6"
                        >
                            <v-radio
                                :true-icon="MdiRadioBoxMarked"
                                :false-icon="MdiRadioboxBlank"
                                value="bad_image"
                                :label="$t('dialogs.reportContent.bad_image')"
                            />
                        </v-col>

                        <v-col
                            class="p-0"
                            cols="12"
                            md="6"
                        >
                            <v-radio
                                :true-icon="MdiRadioBoxMarked"
                                :false-icon="MdiRadioboxBlank"
                                value="spam"
                                :label="$t('dialogs.reportContent.spam')"
                            />
                        </v-col>

                        <v-col
                            class="p-0"
                            cols="12"
                            md="6"
                        >
                            <v-radio
                                :true-icon="MdiRadioBoxMarked"
                                :false-icon="MdiRadioboxBlank"
                                value="other"
                                :label="$t('dialogs.reportContent.other')"
                            />
                        </v-col>
                    </v-row>
                </v-radio-group>

                <h2 class="mb-2 text-lg font-bold">
                    {{ $t('dialogs.reportContent.additional_information') }}
                </h2>

                <v-textarea
                    v-model="reportForm.message"
                    hide-details
                    aria-label="Additional information"
                    :error-messages="reportForm.errors.message"
                />

                <v-checkbox
                    v-model="reportForm.public"
                    :true-icon="MdiCheckboxMarkedOutline"
                    :false-icon="MdiCheckboxBlankOutline"
                    :label="$t('dialogs.reportContent.public_report')"
                    :error-messages="reportForm.errors.public"
                />
            </v-card-text>

            <v-card-actions class="justify-end">
                <v-btn
                    type="submit"
                    color="primary"
                    block
                    :loading="reportForm.processing"
                    :text="$t('general.send')"
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
