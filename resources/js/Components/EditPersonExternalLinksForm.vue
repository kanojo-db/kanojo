<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { useForm } from '@inertiajs/vue3';

import Ameba from '~icons/custom/ameba';
import Fanza from '~icons/custom/fanza';
import MdiLock from '~icons/mdi/lock';
import MdiLockOpen from '~icons/mdi/lock-open';
import Google from '~icons/simple-icons/google';
import IMDb from '~icons/simple-icons/imdb';
import Instagram from '~icons/simple-icons/instagram';
import Line from '~icons/simple-icons/line';
import OnlyFans from '~icons/simple-icons/onlyfans';
import TheMovieDb from '~icons/simple-icons/themoviedatabase';
import TikTok from '~icons/simple-icons/tiktok';
import Twitter from '~icons/simple-icons/twitter';
import Wikidata from '~icons/simple-icons/wikidata';
import YouTube from '~icons/simple-icons/youtube';

import { EditSubroute } from '@/types/enums';

const props = defineProps({
    item: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const itemEditForm = useForm({
    twitter_id: props.item.twitter_id,
    instagram_id: props.item.instagram_id,
    tiktok_id: props.item.tiktok_id,
    ameblo_id: props.item.ameblo_id,
    wikidata_id: props.item.wikidata_id,
    youtube_id: props.item.youtube_id,
    google_id: props.item.google_id,
    imdb_id: props.item.imdb_id,
    fanza_id: props.item.fanza_id,
    tmdb_id: props.item.tmdb_id,
    line_blog_id: props.item.line_blog_id,
    onlyfans_id: props.item.onlyfans_id,
});

const submit = () => {
    itemEditForm.post(
        route('models.external-ids.update', { model: props.item.slug }),
    );
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
                            text="Twitter Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.twitter_id"
                        name="twitter_id"
                        :prepend-inner-icon="Twitter"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="instagram_id"
                            text="Instagram Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.instagram_id"
                        name="instagram_id"
                        :prepend-inner-icon="Instagram"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            name="tiktok_id"
                            text="TikTok Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.tiktok_id"
                        name="tiktok_id"
                        :prepend-inner-icon="TikTok"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="youtube_id"
                            text="YouTube Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.youtube_id"
                        name="youtube_id"
                        :prepend-inner-icon="YouTube"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="ameblo_id"
                            text="Ameblo Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.ameblo_id"
                        name="ameblo_id"
                        :prepend-inner-icon="Ameba"
                    />
                </v-col>

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
            </v-row>

            <v-row class="mt-0">
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
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="fanza_id"
                            text="Fanza Actress ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.fanza_id"
                        name="fanza_id"
                        :prepend-inner-icon="Fanza"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="tmdb_id"
                            text="TMDB Person ID"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.tmdb_id"
                        name="tmdb_id"
                        :prepend-inner-icon="TheMovieDb"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="line_blog_id"
                            text="LINE Blog Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.line_blog_id"
                        name="line_blog_id"
                        :prepend-inner-icon="Line"
                    />
                </v-col>

                <v-col class="pt-0">
                    <div class="mb-2 flex flex-row items-center">
                        <mdi-lock-open class="mr-2 text-stone-500" />

                        <v-label
                            for="onlyfans_id"
                            text="OnlyFans Username"
                        />
                    </div>

                    <v-text-field
                        v-model="itemEditForm.onlyfans_id"
                        name="onlyfans_id"
                        :prepend-inner-icon="OnlyFans"
                    />
                </v-col>
            </v-row>

            <v-row class="mt-0">
                <v-col class="pt-0">
                    <v-btn
                        color="primary"
                        type="submit"
                    >
                        {{ $t('general.saveChanges') }}
                    </v-btn>
                </v-col>
            </v-row>
        </v-form>
    </v-window-item>
</template>
