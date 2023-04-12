<script setup lang="ts">
import { useDialogPluginComponent } from 'quasar';
import { reactive } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    reportForm: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits([...useDialogPluginComponent.emits]);

const reportForm = reactive(props.reportForm);

const { dialogRef, onDialogHide, onDialogOK } = useDialogPluginComponent();

function onOKClick() {
    emit('ok', reportForm);

    onDialogOK();
}
</script>

<template>
    <q-dialog
        ref="dialogRef"
        @hide="onDialogHide"
    >
        <q-card style="width: 700px; max-width: 80vw">
            <q-card-section class="bg-primary text-white row items-center">
                <div class="text-weight-bold text-h6">
                    {{ $t('web.dialogs.report_content.title', { title }) }}
                </div>
                <q-space />
                <q-btn
                    v-close-popup
                    icon="mdi-close"
                    flat
                    round
                    dense
                />
            </q-card-section>

            <q-separator />

            <q-card-section>
                <q-form
                    class="column"
                    @submit.prevent="onOKClick"
                >
                    <div class="row">
                        <h2 class="text-h6 text-weight-bold q-mt-none q-mb-sm">
                            <q-icon name="mdi-flag" />
                            {{
                                $t('web.dialogs.report_content.type_of_problem')
                            }}
                        </h2>
                    </div>

                    <div class="row">
                        <div class="col column">
                            <q-radio
                                v-model="reportForm.type"
                                val="duplicate"
                                :label="
                                    $t('web.dialogs.report_content.duplicate')
                                "
                            />
                            <q-radio
                                v-model="reportForm.type"
                                val="incorrect"
                                :label="
                                    $t('web.dialogs.report_content.incorrect')
                                "
                            />
                            <q-radio
                                v-model="reportForm.type"
                                val="bad_image"
                                :label="
                                    $t('web.dialogs.report_content.bad_image')
                                "
                            />
                        </div>

                        <div class="col column">
                            <q-radio
                                v-model="reportForm.type"
                                val="spam"
                                :label="$t('web.dialogs.report_content.spam')"
                            />
                            <q-radio
                                v-model="reportForm.type"
                                val="other"
                                :label="$t('web.dialogs.report_content.other')"
                            />
                        </div>
                    </div>

                    <div class="row">
                        <h2 class="text-h6 text-weight-bold q-mt-none q-mb-sm">
                            <q-icon name="mdi-comment-text" />
                            {{
                                $t(
                                    'web.dialogs.report_content.additional_information',
                                )
                            }}
                        </h2>
                    </div>

                    <div class="row">
                        <q-input
                            v-model="reportForm.message"
                            type="textarea"
                            filled
                            class="full-width"
                        />
                    </div>

                    <div class="row q-mt-md">
                        <q-checkbox
                            v-model="reportForm.public"
                            :label="
                                $t('web.dialogs.report_content.public_report')
                            "
                        />
                    </div>

                    <q-btn
                        type="submit"
                        label="Upload"
                        color="primary"
                        class="col-12 q-mt-md"
                    />
                </q-form>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
