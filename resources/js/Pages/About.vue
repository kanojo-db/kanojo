<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ValueCountGraph from "../Components/ValueCountGraph.vue";

const { movieCount, modelCount, tagCount } = defineProps({
    movieCount: Number,
    modelCount: Number,
    tagCount: Number,
    birthCounts: Object,
    heightCounts: Object,
    bustCounts: Object,
    waistCounts: Object,
    hipCounts: Object,
    cupCounts: Object,
});
</script>

<template>
    <AppLayout title="About Kanojo">
        <div class="column fit bg-grey-8 q-pa-xl justify-center items-center">
            <h1 class="text-h1 text-white text-center text-weight-thin">
                Kanojo is a
                <span class="text-weight-medium">community built</span>
                Japanese<br />
                adult video and gravure movie database
            </h1>

            <p class="text-center text-white text-h4">
                Every piece of data is user-submitted and user-curated,<br />
                with a focus on providing high quality information.
            </p>

            <div class="column q-my-xl">
                <div
                    class="row q-my-sm text-white"
                    style="width: 45em; height: 6.5em"
                >
                    <div class="col-1 text-h2 text-weight-bolder text-primary">
                        1
                    </div>
                    <div class="col text-body1">
                        An extensive database of information on movies, studios
                        and models, complete with images. It includes everything
                        you need to find your next favorite title, from content
                        tags to detailed model information.
                    </div>
                </div>
                <div
                    class="row q-my-sm text-white"
                    style="width: 45em; height: 6.5em"
                >
                    <div class="col-1 text-h2 text-weight-bolder text-primary">
                        2
                    </div>
                    <div class="col text-body1">
                        No more need to scrape online stores or evade IP bans to
                        get the information you need. We provide a comprehensive
                        and easy-to-use API for developers, to integrate our
                        data with your tool or service.
                    </div>
                </div>
                <div
                    class="row q-my-sm text-white"
                    style="width: 45em; height: 6.5em"
                >
                    <div class="col-1 text-h2 text-weight-bolder text-primary">
                        3
                    </div>
                    <div class="col text-body1">
                        Built by people like you. We made Kanojo to solve some
                        of the issues we have had with other sites and tools
                        over the past 10 years.
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
            <h2 class="text-h2 q-mt-none q-mb-none">Some Stats</h2>
            <p class="text-subtitle1">Everybody likes to see numbers. Here are a few we think you'll find interesting.</p>
            <div class="row q-mt-md">
                <div class="col-2">
                    <span class="text-h3 text-weight-medium">{{ movieCount }}</span><br/>
                    <span class="text-h5 text-weight-bolder">Movies</span>
                </div>
                <div class="col-2">
                    <span class="text-h3 text-weight-medium">{{ modelCount }}</span><br/>
                    <span class="text-h5 text-weight-bolder">Models</span>
                </div>
                <div class="col-2">
                    <span class="text-h3 text-weight-medium">{{ tagCount }}</span><br/>
                    <span class="text-h5 text-weight-bolder">Categories</span>
                </div>
            </div>
            <div class="row q-gutter-md q-mt-sm">
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per year of birth*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="birthCounts" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known birth year.</span>
                </div>
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per height*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="heightCounts" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known height.</span>
                </div>
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per bust size*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="bustCounts" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known bust size.</span>
                </div>
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per waist size*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="waistCounts" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known waist size.</span>
                </div>
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per hip size*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="hipCounts" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known hip size.</span>
                </div>
                <div class="col-3 q-mt-lg">
                    <span class="text-h5 text-weight-bolder">Distribution of models per bra cup size*</span>
                    <div class="q-mt-md">
                        <ValueCountGraph :counts="cupCounts" :custom-values="['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P']" />
                    </div>
                    <span class="text-caption text-grey-4">* Data only includes models with a known bra cup size.</span>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
