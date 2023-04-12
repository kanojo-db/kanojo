<script setup lang="ts">
import { useDialogPluginComponent } from 'quasar';
import { reactive } from 'vue';

const props = defineProps({
    uploadForm: {
        type: Object,
        required: true,
    },
});

// Make a local copy of the upload form so we can modify it without mutating the original
const uploadForm = reactive(props.uploadForm);

const emit = defineEmits([...useDialogPluginComponent.emits]);

const { dialogRef, onDialogHide, onDialogOK } = useDialogPluginComponent();

function onOKClick() {
    emit('ok', uploadForm);

    onDialogOK();
}
</script>

<template>
    <q-dialog
        ref="dialogRef"
        @hide="onDialogHide"
    >
        <q-card>
            <q-card-section class="bg-primary text-white row items-center">
                <div class="text-weight-bold text-h6">
                    {{ $t('web.dialogs.media_upload.title') }}
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
                <p class="text-body1">
                    {{ $t('web.dialogs.media_upload.instructions') }}
                </p>
                <p class="text-body1">
                    {{ $t('web.dialogs.media_upload.requirements_heading') }}
                </p>
                <ul class="text-body1">
                    <li>{{ $t('web.dialogs.media_upload.requirements_1') }}</li>
                    <li>{{ $t('web.dialogs.media_upload.requirements_2') }}</li>
                    <li>{{ $t('web.dialogs.media_upload.requirements_3') }}</li>
                </ul>
                <q-form
                    class="row"
                    @submit.prevent="onOKClick"
                >
                    <q-file
                        v-model="uploadForm.media"
                        class="col-grow"
                        filled
                        label="Select a file"
                        @update="onOKClick"
                    />

                    <q-btn
                        type="submit"
                        label="Upload"
                        color="primary"
                        class="q-ml-md"
                    />
                </q-form>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
