<script setup lang="ts">
import type { Movie } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

import DMMDotCom from '~icons/custom/dmmdotcom';
import Fanza from '~icons/custom/fanza';
import MdiHelp from '~icons/mdi/help';
import MdiLockOpen from '~icons/mdi/lock-open';
import Google from '~icons/simple-icons/google';
import IMDb from '~icons/simple-icons/imdb';
import TheMovieDb from '~icons/simple-icons/themoviedatabase';
import Wikidata from '~icons/simple-icons/wikidata';

import { EditSubroute } from '@/types/enums';
import { useItemRouteParams } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const itemEditForm = useForm({
    imdb_id: props.item.imdb_id,
    tmdb_id: props.item.tmdb_id,
    fanza_id: props.item.fanza_id,
    mgstage_id: props.item.mgstage_id,
    dmm_id: props.item.dmm_id,
    fc2_id: props.item.fc2_id,
    wikidata_id: props.item.wikidata_id,
    google_id: props.item.google_id,
});

const routeParams = useItemRouteParams(props.item);

const isSaving = ref(false);

const submit = () => {
    isSaving.value = true;

    itemEditForm.post(route('movies.external-ids.update', routeParams.value), {
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
                            for="imdb_id"
                            text="IMDb ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.imdb_id"
                        name="imdb_id"
                        :prepend-inner-icon="IMDb"
                        :error-messages="itemEditForm.errors.imdb_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="tmdb_id"
                            text="The Movie Database ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.tmdb_id"
                        name="tmdb_id"
                        :prepend-inner-icon="TheMovieDb"
                        :error-messages="itemEditForm.errors.tmdb_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            name="fanza_id"
                            text="Fanza ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.fanza_id"
                        name="fanza_id"
                        :prepend-inner-icon="Fanza"
                        :error-messages="itemEditForm.errors.fanza_id"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="mgstage_id"
                            text="MGStage ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.mgstage_id"
                        name="mgstage_id"
                        :prepend-inner-icon="MdiHelp"
                        :error-messages="itemEditForm.errors.mgstage_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="dmm_id"
                            text="DMM ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.dmm_id"
                        name="dmm_id"
                        :prepend-inner-icon="DMMDotCom"
                        :error-messages="itemEditForm.errors.dmm_id"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="fc2_id"
                            text="FC2 ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.fc2_id"
                        name="fc2_id"
                        :prepend-inner-icon="MdiHelp"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="wikidata_id"
                            text="Wikidata ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.wikidata_id"
                        name="wikidata_id"
                        :prepend-inner-icon="Wikidata"
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
                    />
                </v-col>

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
