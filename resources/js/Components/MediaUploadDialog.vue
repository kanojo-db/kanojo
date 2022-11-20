<script setup>
import { useDialogPluginComponent } from "quasar";

const { uploadForm, onSubmit } = defineProps({
    uploadForm: Object,
    onSubmit: Function,
});

defineEmits([...useDialogPluginComponent.emits]);

const { dialogRef, onDialogHide, onDialogOK, onDialogCancel } =
    useDialogPluginComponent();

function onOKClick() {
    onSubmit();
    onDialogOK();
}
</script>

<template>
    <q-dialog ref="dialogRef" @hide="onDialogHide">
        <q-card>
            <q-card-section class="bg-primary text-white row items-center">
                <div class="text-weight-bold text-h6">Upload Profile</div>
                <q-space />
                <q-btn icon="mdi-close" flat round dense v-close-popup />
            </q-card-section>

            <q-separator />

            <q-card-section>
                <p class="text-body1">Select a profile picture by clicking on the button below and selecting an image on your computer.</p>
                <p class="text-body1">Profile images must meet the following criteria:</p>
                <ul class="text-body1">
                    <li>A maximum resolution of 2000x3000</li>
                    <li>A minimum resolution of 300x450</li>
                    <li>Aspect ratio of 1:1.5 (2:3)</li>
                </ul>
                <q-form @submit.prevent="onOKClick" class="row">
                    <q-file v-model="uploadForm.media" class="col-grow" filled label="Select a file" @update="onOKClick" />

                    <q-btn type="submit" label="Upload" color="primary" class="q-ml-md" />
                </q-form>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
