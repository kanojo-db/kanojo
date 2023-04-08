<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { useQuasar } from 'quasar';
import { PropType, ref } from 'vue';
import { useI18n } from 'vue-i18n';

import DialogMediaUpload from '@/Components/DialogMediaUpload.vue';
import MovieTabBar from '@/Components/MovieTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Movie } from '@/types/models';
import { useFirstImage, useTitle } from '@/utils/item';

enum MovieMediaCollection {
    FrontCover = 'front_cover',
    FullCover = 'full_cover',
}

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
    images: {
        type: Object,
        required: true,
    },
    mediaCollectionCount: {
        type: Object,
        required: true,
    },
});

const currentRoute = route();

const currentCollectionName = ref(currentRoute.params?.type ?? 'front_cover');

const locale = useI18n().locale;

const title = useTitle(props.movie, locale.value);

const posterUrl = useFirstImage(props.movie);

const mediaUploadForm = useForm({
    media: null,
    collection_name: currentCollectionName.value,
});

const mediaFormSubmit = () => {
    mediaUploadForm.post(route('movies.media.store', { movie: props.movie }), {
        preserveScroll: true,
        onSuccess: () => {
            router.reload();
        },
    });
};

const $q = useQuasar();

const openMediaUploadDialog = () => {
    $q.dialog({
        component: DialogMediaUpload,
        componentProps: {
            uploadForm: mediaUploadForm,
            onSubmit: mediaFormSubmit,
        },
    });
};
</script>

<template>
    <div class="col bg-grey-3">
        <MovieTabBar :movie="props.movie" />
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
                        {{ title }}
                    </h1>
                    <Link
                        :href="
                            route('movies.show', {
                                movie: props.movie.slug,
                            })
                        "
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
                    class="my-card"
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
                        <q-item
                            v-for="collectionType in MovieMediaCollection"
                            :key="collectionType"
                            clickable
                            :href="
                                route('movies.media.index', {
                                    movie: props.movie.slug,
                                    type: collectionType,
                                })
                            "
                        >
                            <q-item-section>{{
                                $t(`web.media.types.${collectionType}`)
                            }}</q-item-section>
                            <q-item-section side>
                                <q-chip class="bg-grey-6 text-white">
                                    {{
                                        mediaCollectionCount[collectionType] ??
                                        0
                                    }}
                                </q-chip>
                            </q-item-section>
                        </q-item>
                    </q-list>
                </q-card>
            </div>
            <div class="col col-10">
                <div v-if="images.length === 0">No posters found.</div>
                <div
                    v-else
                    class="fit row wrap justify-start items-start content-start"
                >
                    <q-card
                        v-for="poster in images"
                        :key="poster.id"
                        class="my-card"
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
