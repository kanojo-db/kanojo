<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { useQuasar } from 'quasar';
import { computed, defineProps, ref } from 'vue';

import { useName } from '@/utils/item';

import { useAdmin } from '../utils/user';
import DialogReportContent from './DialogReportContent.vue';
import DialogShareLink from './DialogShareLink.vue';

const props = defineProps({
    studio: {
        type: Object,
        required: true,
    },
});

const fullUrl = ref(window.location.href.split('?')[0]);

const isAdmin = useAdmin();

const $q = useQuasar();

const name = useName(props.studio);

const openShareLinkDialog = () => {
    $q.dialog({
        component: DialogShareLink,
        componentProps: {
            url: fullUrl.value,
            title: name,
        },
    });
};

const reportForm = useForm({
    type: null,
    message: '',
    public: true,
});

const openReportDialog = () => {
    $q.dialog({
        component: DialogReportContent,
        componentProps: {
            title: name,
            reportForm: reportForm,
            onSubmit: () => {
                reportForm.post(
                    route('movies.reports.store', {
                        movie: props.studio.slug,
                    }),
                );

                reportForm.reset();
            },
        },
    });
};
</script>

<template>
    <div class="row bg-grey-4 q-py-sm justify-center items-center">
        <q-btn-group flat>
            <q-btn-dropdown
                flat
                :label="$t('web.movie.tabs.overview.title')"
            >
                <q-list
                    dense
                    style="min-width: 100px"
                >
                    <q-item
                        v-close-popup
                        clickable
                        @click="
                            $inertia.get(
                                route('movies.show', {
                                    movie: props.studio.slug,
                                }),
                            )
                        "
                    >
                        <q-item-section>
                            {{ $t('web.movie.tabs.overview.main') }}
                        </q-item-section>
                    </q-item>
                    <q-separator class="q-my-xs" />
                    <q-item
                        v-close-popup
                        clickable
                        @click="openReportDialog"
                    >
                        {{ $t('web.movie.tabs.overview.report') }}
                    </q-item>
                </q-list>
            </q-btn-dropdown>
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Movie/Media',
                }"
                label="Media"
                @click="
                    $inertia.get(
                        route('movies.media.index', {
                            movie: props.studio.slug,
                        }),
                    )
                "
            />
            <q-btn
                flat
                label="History"
                @click="
                    $inertia.get(
                        route('movies.history.index', {
                            movie: props.studio.slug,
                        }),
                    )
                "
            />
            <q-btn
                flat
                label="Edit"
                @click="
                    $inertia.get(
                        route('movies.edit', { movie: props.studio.slug }),
                    )
                "
            />
            <q-btn-dropdown
                flat
                :label="$t('web.movie.tabs.share.title')"
            >
                <q-list
                    dense
                    style="min-width: 100px"
                >
                    <q-item
                        v-close-popup
                        clickable
                        @click="openShareLinkDialog"
                    >
                        <q-item-section>
                            {{ $t('web.movie.tabs.share.link') }}
                        </q-item-section>
                    </q-item>
                    <q-item
                        v-close-popup
                        clickable
                        :href="`https://www.facebook.com/sharer/sharer.php?u=${fullUrl}`"
                    >
                        <q-item-section>
                            {{ $t('web.movie.tabs.share.facebook') }}
                        </q-item-section>
                    </q-item>
                    <q-item
                        v-close-popup
                        clickable
                        :href="`https://twitter.com/intent/tweet?url=${fullUrl}`"
                    >
                        <q-item-section>
                            {{ $t('web.movie.tabs.share.twitter') }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-btn-dropdown>
            <q-btn-dropdown
                v-if="isAdmin"
                flat
                :label="$t('web.movie.tabs.manage.title')"
            >
                <q-list
                    dense
                    style="min-width: 100px"
                >
                    <q-item
                        clickable
                        @click="
                            $inertia.get(
                                route('movies.reports.index', {
                                    movie: props.studio.slug,
                                }),
                            )
                        "
                    >
                        <q-item-section>
                            {{ $t('web.general.pages.content_reports') }}
                        </q-item-section>
                    </q-item>
                </q-list>
            </q-btn-dropdown>
        </q-btn-group>
    </div>
</template>
