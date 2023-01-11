<script setup>
import { Link } from '@inertiajs/inertia-vue3';
import { DateTime } from 'luxon';
import { computed } from 'vue';

import MovieTabBar from '@/Components/MovieTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    movie: {
        type: Object,
        required: true,
    },
});

const posterUrl = computed(() => {
    if (props.movie?.media && props.movie.media.length > 0) {
        return props.movie.media.filter(
            (m) => m.collection_name === 'poster',
        )?.[0].original_url;
    }

    return null;
});

const title = computed(() =>
    props.movie.title.en ? props.movie.title.en : props.movie.title.jp,
);
</script>

<template>
    <AppLayout :title="`${title} - Reports`">
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
                        <h1
                            class="text-h4 q-mt-none q-mb-none ellipsis-2-lines"
                        >
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
        <div class="q-mx-md">
            <div class="row q-mb-md">
                <h2 class="text-h5 q-mb-none">
                    {{ props.movie.reports.length }} Reports
                </h2>
            </div>
            <div class="fit grid-2">
                <q-card
                    v-for="(report, index) in props.movie.reports"
                    :key="`movie-report-${index}`"
                    flat
                    bordered
                >
                    <q-card-section
                        class="justify-start items-start"
                        horizontal
                    >
                        <q-card-section
                            class="column justify-center items-start bg-grey-2 col-4 q-pa-md"
                            horizontal
                        >
                            <div class="row items-center">
                                <q-avatar
                                    size="40px"
                                    class="bg-grey-1"
                                >
                                    <q-icon
                                        name="mdi-account"
                                        size="20px"
                                        color="grey-2"
                                    />
                                </q-avatar>
                                <div class="column q-ml-md">
                                    <h3 class="text-h6 q-mt-none q-mb-none">
                                        {{ report.reporter.name }}
                                    </h3>
                                    <p class="text-caption q-mb-none">
                                        {{
                                            $t('web.profile.member_since', {
                                                date: DateTime.fromISO(
                                                    report.reporter.created_at,
                                                ).toLocaleString(
                                                    DateTime.DATE_FULL,
                                                ),
                                            })
                                        }}
                                    </p>
                                </div>
                            </div>
                            <div class="row q-my-sm">
                                <p class="text-body1 q-mb-none">
                                    {{
                                        $t('web.movie.reports.opened_at', {
                                            date: DateTime.fromISO(
                                                report.created_at,
                                            ).toRelative(),
                                        })
                                    }}
                                </p>
                            </div>
                            <div class="row">
                                <p class="text-body1 q-my-none">
                                    <span class="text-weight-bold">
                                        {{
                                            $t('web.movie.reports.report_type')
                                        }}
                                    </span>
                                    {{
                                        $t(
                                            `web.dialogs.report_content.${report.type}`,
                                        )
                                    }}
                                </p>
                            </div>
                        </q-card-section>

                        <q-card-section
                            class="justify-start items-start col-8 q-pa-md"
                            horizontal
                        >
                            <p
                                class="text-body1"
                                style="white-space: pre"
                                v-text="report.message"
                            />
                        </q-card-section>
                    </q-card-section>
                </q-card>
            </div>
        </div>
    </AppLayout>
</template>
