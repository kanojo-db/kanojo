<script setup>
import ValueCountGraph from '@/Components/ValueCountGraph.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    movieCount: {
        type: Number,
        required: true,
    },
    modelCount: {
        type: Number,
        required: true,
    },
    tagCount: {
        type: Number,
        required: true,
    },
    moviePerYearCount: {
        type: Object,
        required: true,
    },
    birthCounts: {
        type: Object,
        required: true,
    },
    heightCounts: {
        type: Object,
        required: true,
    },
    bustCounts: {
        type: Object,
        required: true,
    },
    waistCounts: {
        type: Object,
        required: true,
    },
    hipCounts: {
        type: Object,
        required: true,
    },
    cupCounts: {
        type: Object,
        required: true,
    },
    averageMoviesPerModel: {
        type: Number,
        required: true,
    },
});
</script>

<template>
    <div class="column fit bg-grey-8 q-pa-xl justify-center items-center">
        <i18n-t
            keypath="web.about.title"
            tag="h1"
            for="community"
            class="text-h1 text-white text-center text-weight-thin"
        >
            <span class="text-weight-regular">
                {{ $t('web.about.community') }}
            </span>
        </i18n-t>

        <i18n-t
            keypath="web.about.subtitle"
            tag="p"
            class="text-center text-white text-h4 q-mb-none"
        >
            <br />
        </i18n-t>

        <div class="column q-my-xl">
            <div
                class="row q-my-sm text-white"
                style="width: 45em; height: 6.5em"
            >
                <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                <div class="col-1 text-h2 text-weight-bolder text-primary">
                    1
                </div>
                <div class="col text-body1">
                    {{ $t('web.about.point1') }}
                </div>
            </div>
            <div
                class="row q-my-sm text-white"
                style="width: 45em; height: 6.5em"
            >
                <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                <div class="col-1 text-h2 text-weight-bolder text-primary">
                    2
                </div>
                <div class="col text-body1">
                    {{ $t('web.about.point2') }}
                </div>
            </div>
            <div
                class="row q-my-sm text-white"
                style="width: 45em; height: 6.5em"
            >
                <!-- eslint-disable-next-line @intlify/vue-i18n/no-raw-text -- Non-translatable number -->
                <div class="col-1 text-h2 text-weight-bolder text-primary">
                    3
                </div>
                <div class="col text-body1">
                    {{ $t('web.about.point3') }}
                </div>
            </div>
        </div>

        <q-btn
            class="q-mt-lg q-mb-xl"
            color="primary"
            size="lg"
            label="Contact Us"
        />
    </div>
    <div class="column fit q-pa-xl">
        <h2 class="text-h2 q-mt-none q-mb-sm">
            {{ $t('web.about.statsHeader') }}
        </h2>
        <p class="text-subtitle1 q-mb-xs">
            {{ $t('web.about.statsIntro') }}
        </p>
        <div class="row q-my-md">
            <div class="col-2">
                <span class="text-secondary text-h3 text-weight-bolder">
                    {{ props.movieCount.toLocaleString() }}
                </span>
                <br />
                <span class="text-h5 text-weight-bolder">
                    {{ $t('web.welcome.countMovies') }}
                </span>
            </div>
            <div class="col-2">
                <span class="text-secondary text-h3 text-weight-bolder">
                    {{ props.modelCount.toLocaleString() }}
                </span>
                <br />
                <span class="text-h5 text-weight-bolder">
                    {{ $t('web.welcome.countModels') }}
                </span>
            </div>
            <div class="col-2">
                <span class="text-secondary text-h3 text-weight-bolder">
                    {{ props.tagCount.toLocaleString() }}
                </span>
                <br />
                <span class="text-h5 text-weight-bolder">
                    {{ $t('web.welcome.countCategories') }}
                </span>
            </div>
            <div class="col-2">
                <span class="text-secondary text-h3 text-weight-bolder">
                    {{
                        props.averageMoviesPerModel.toFixed(1).toLocaleString()
                    }}
                </span>
                <br />
                <span class="text-h5 text-weight-bolder">
                    {{ $t('web.about.averageMoviesPerModel') }}
                </span>
            </div>
        </div>
        <div class="fit grid-3">
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    {{ $t('web.about.distributionMoviesPerYear') }}
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph :counts="props.moviePerYearCount" />
                </div>
                <span class="text-caption text-grey-4">
                    {{ $t('web.about.distributionMoviesPerYearInfo') }}
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per year of birth*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph :counts="props.birthCounts" />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known birth year.
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per height*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph
                        :counts="props.heightCounts"
                        :highlight-value="158"
                    />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known height.
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per bust size*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph
                        :counts="props.bustCounts"
                        :highlight-value="78"
                    />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known bust size.
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per waist size*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph
                        :counts="props.waistCounts"
                        :highlight-value="60"
                    />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known waist size.
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per hip size*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph
                        :counts="props.hipCounts"
                        :highlight-value="85"
                    />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known hip size.
                </span>
            </div>
            <div>
                <span class="text-h5 text-weight-bolder q-mb-md">
                    Distribution of models per bra cup size*
                </span>
                <div class="q-mt-sm">
                    <ValueCountGraph
                        :counts="props.cupCounts"
                        :custom-values="[
                            'A',
                            'B',
                            'C',
                            'D',
                            'E',
                            'F',
                            'G',
                            'H',
                            'I',
                            'J',
                            'K',
                            'L',
                            'M',
                            'N',
                            'O',
                            'P',
                            'Q',
                            'R',
                            'S',
                            'T',
                        ]"
                        :highlight-value="'D'"
                    />
                </div>
                <span class="text-caption text-grey-4">
                    * Data only includes models with a known bra cup size.
                </span>
            </div>
        </div>
        <p class="text-italic q-mt-lg q-mb-none">
            Values highlighted in the graphs above represent the Japanese
            national average for that statistic.<br />
            Height and measurements averages are based on data collected by
            Japanese fashion magazine CanCam in 2015 among a set of 1000
            participants in their 20s.<br />
            Bra cup size average is based on data collected by cosmetics maker
            Love Cosme in 2018 among 24,819 participants.
        </p>
    </div>
</template>
