<script setup lang="ts">
import type { Studio } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import MdiLock from '~icons/mdi/lock';
import MdiLockOpen from '~icons/mdi/lock-open';
import OfficeBuilding from '~icons/mdi/office-building';
import Google from '~icons/simple-icons/google';
import Twitter from '~icons/simple-icons/twitter';
import Wikidata from '~icons/simple-icons/wikidata';

import { EditSubroute } from '@/types/enums';
import { useItemRouteParams } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Studio>,
        required: true,
    },
});

const itemEditForm = useForm({
    twitter_id: props.item.twitter_id,
    wikidata_id: props.item.wikidata_id,
    google_id: props.item.google_id,
    corporate_number: props.item.corporate_number,
});

const routeParams = useItemRouteParams(props.item);

const isSaving = ref(false);

const submit = () => {
    isSaving.value = true;

    itemEditForm.post(route('studios.external-ids.update', routeParams.value), {
        onFinish: () => {
            isSaving.value = false;
        },
    });
};
</script>

<template>
    <v-window-item :value="EditSubroute.ExternalIds">
        <v-form @submit.prevent="submit">
            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="twitter_id"
                            text="Twitter ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.twitter_id"
                        name="twitter_id"
                        :prepend-inner-icon="Twitter"
                        :error-messages="itemEditForm.errors.twitter_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="google_id"
                            text="Google Knowledge Graph ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.google_id"
                        name="google_id"
                        :prepend-inner-icon="Google"
                        :error-messages="itemEditForm.errors.google_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            name="wikidata_id"
                            text="Wikidata ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.wikidata_id"
                        name="wikidata_id"
                        :prepend-inner-icon="Wikidata"
                        :error-messages="itemEditForm.errors.wikidata_id"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock class="mr-2 text-stone-500" />

                        <v-label
                            for="corporate_number"
                            text="Corporate Number"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.corporate_number"
                        name="corporate_number"
                        :prepend-inner-icon="OfficeBuilding"
                        :error-messages="itemEditForm.errors.corporate_number"
                    />
                </v-col>

                <v-col />

                <v-col />
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <v-btn
                        color="primary"
                        type="submit"
                        :loading="isSaving"
                    >
                        {{ $t('general.saveChanges') }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-window-item>
</template>
