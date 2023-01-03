<script setup>
import { useDialogPluginComponent } from 'quasar';

const props = defineProps({
    tokenForm: {
        type: Object,
        required: true,
    },
    onSubmit: {
        type: Function,
        required: true,
    },
});

defineEmits([...useDialogPluginComponent.emits]);

const { dialogRef, onDialogHide, onDialogOK } = useDialogPluginComponent();

function onOKClick() {
    props.onSubmit();
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
                    {{ $t('web.dialogs.create_token.title') }}
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
                    class="row"
                    @submit.prevent="onOKClick"
                >
                    <p class="text-body1">
                        {{ $t('web.dialogs.create_token.description') }}
                    </p>
                    <q-input
                        v-model="tokenForm.name"
                        label="Token Name"
                        class="col-12"
                        filled
                        required
                        maxlength="255"
                    />

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
