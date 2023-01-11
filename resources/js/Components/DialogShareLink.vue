<script setup>
import { useDialogPluginComponent } from 'quasar';

const props = defineProps({
    url: {
        type: String,
        required: true,
    },
    title: {
        type: String,
        required: true,
    },
});

defineEmits([...useDialogPluginComponent.emits]);

const { dialogRef, onDialogHide } = useDialogPluginComponent();
</script>

<template>
    <q-dialog
        ref="dialogRef"
        @hide="onDialogHide"
    >
        <q-card>
            <q-card-section class="bg-primary text-white row items-center">
                <div class="text-weight-bold text-h6">
                    {{
                        $t('web.dialogs.share_link.title', { pageName: title })
                    }}
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
                    <q-input
                        v-model="props.url"
                        readonly
                        :label="$t('web.dialogs.share_link.url')"
                        class="col-12"
                        filled
                    />
                </q-form>
            </q-card-section>
        </q-card>
    </q-dialog>
</template>
