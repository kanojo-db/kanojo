<script setup lang="ts">
import type { Studio } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import MdiLockOpen from '~icons/mdi/lock-open';

import { EditSubroute } from '@/types/enums';
import { getName, useItemRouteParams } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Studio>,
        required: true,
    },
});

const locale = useI18n().locale;

const itemEditForm = useForm({
    name: getName(props.item, locale.value, false) ?? '',
    originalName: props.item.name['ja-JP'],
    foundedDate: props.item.founded_date,
});

const routeParams = useItemRouteParams(props.item);

const submit = () => {
    itemEditForm.patch(route('studios.update', routeParams.value));
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
                        v-model="itemEditForm.originalName"
                        name="original_name"
                        required
                        :error-messages="$page.props.errors.original_name"
                    />
                </v-col>
            </v-row>

            <v-row>
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
                        :error-messages="$page.props.errors.name"
                    />
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="founded_date"
                            text="Founded Date"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.foundedDate"
                        name="founded_date"
                        type="date"
                        clearable
                        persistent-clear
                        :error-messages="$page.props.errors.founded_date"
                    />
                </v-col>

                <v-col />

                <v-col />
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
