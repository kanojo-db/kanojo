<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { useQuasar } from 'quasar';
import { PropType } from 'vue';
import { useI18n } from 'vue-i18n';

import DialogMediaUpload from '@/Components/DialogMediaUpload.vue';
import PersonTabBar from '@/Components/PersonTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Person } from '@/types/models';
import { useFirstImage, useName } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    person: {
        type: Object as PropType<Person>,
        required: true,
    },
    posters: {
        type: Object,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.person, locale);

const posterUrl = useFirstImage(props.person, 'profile');

const mediaUploadForm = useForm({
    media: null,
    collection_name: 'profile',
});

const $q = useQuasar();

const openMediaUploadDialog = () => {
    $q.dialog({
        component: DialogMediaUpload,
        componentProps: {
            uploadForm: mediaUploadForm,
        },
    }).onOk((data: typeof mediaUploadForm) => {
        mediaUploadForm.media = data.media;
        mediaUploadForm.collection_name = data.collection_name;

        mediaUploadForm.post($route('models.media.store', props.person), {
            preserveScroll: true,
            onSuccess: () => {
                mediaUploadForm.reset();
            },
        });
    });
};
</script>

<template>
    <div class="col bg-grey-3">
        <PersonTabBar :person="props.person" />
        <div class="row q-py-md q-px-md">
            <div
                class="q-pl-none q-mr-lg"
                style="max-width: 300px"
            >
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="80px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center"
                    style="width: 80px; height: 120px"
                >
                    <q-icon
                        name="mdi-help"
                        size="60px"
                        color="grey-2"
                    />
                </div>
            </div>
            <div class="col flex">
                <div
                    class="column full-height justify-start items-start q-mb-sm"
                >
                    <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                        {{ name }}
                    </h1>
                    <Link
                        :href="$route('models.show', props.person)"
                        class="text-subtitle1"
                    >
                        <q-icon
                            name="mdi-arrow-left"
                            size="14px"
                        />
                        Back to Overview
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <div class="q-ma-md">
        <div class="row q-col-gutter-lg full-width">
            <div class="col-2 q-pl-none">
                <q-card
                    flat
                    bordered
                >
                    <q-card-section
                        class="bg-primary text-white row items-center"
                    >
                        <div class="text-weight-bold text-h6">Media</div>
                        <q-space />
                        <q-btn
                            flat
                            round
                            icon="mdi-plus"
                            @click="openMediaUploadDialog"
                        />
                    </q-card-section>

                    <q-separator />

                    <q-list
                        dense
                        bordered
                        padding
                    >
                        <q-item clickable>
                            <q-item-section>Profile</q-item-section>
                            <q-item-section side>
                                <q-chip class="bg-grey-6 text-white">
                                    {{ posters.length }}
                                </q-chip>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-10">
                <div v-if="posters.length === 0">No posters found.</div>
                <div
                    v-else
                    class="fit row wrap justify-start items-start content-start q-gutter-md"
                >
                    <q-card
                        v-for="poster in posters"
                        :key="poster.id"
                        flat
                        bordered
                    >
                        <q-img
                            :src="poster.original_url"
                            width="250px"
                            :ratio="2 / 3"
                            fit="cover"
                        />

                        <q-card-section class="row bg-grey-2">
                            <q-btn
                                unelevated
                                round
                                :color="hasLiked ? 'secondary' : 'primary'"
                                icon="mdi-thumb-up"
                                @click="likeMovie"
                            >
                                <q-tooltip class="bg-primary"> Like </q-tooltip>
                            </q-btn>

                            <q-space />

                            <q-btn
                                unelevated
                                round
                                :color="hasDisliked ? 'secondary' : 'primary'"
                                icon="mdi-thumb-down"
                                @click="dislikeMovie"
                            >
                                <q-tooltip class="bg-primary">
                                    Dislike
                                </q-tooltip>
                            </q-btn>
                        </q-card-section>

                        <q-separator />

                        <q-card-section class="text-subtitle1">
                            <strong class="text-weight-bold">Added on</strong
                            ><br />
                            {{
                                DateTime.fromISO(
                                    poster.created_at,
                                ).toLocaleString(DateTime.DATETIME_MED)
                            }}
                        </q-card-section>
                    </q-card>
                </div>
            </div>
        </div>
    </div>
</template>
