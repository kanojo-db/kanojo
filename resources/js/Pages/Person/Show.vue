<script setup>
import { computed } from "vue";

import AppLayout from "@/Layouts/AppLayout.vue";
import PersonTabBar from "@/Components/PersonTabBar.vue";
import Card from "@/Components/Card.vue";

const { person } = defineProps({
    person: Object,
});

const posterUrl = computed(() => {
    if (person?.media && person.media.length > 0) {
        return person.media.filter((m) => m.collection_name === "profile")?.[0]
            .original_url;
    }

    return null;
});
</script>

<template>
    <AppLayout>
        <div class="col bg-grey-3">
            <PersonTabBar :person="person" />
            <div class="row q-py-lg q-px-md">
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="300px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders q-mr-lg"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center q-mr-lg"
                    style="width: 300px; height: 450px"
                >
                    <q-icon name="mdi-help" size="150px" color="grey-2" />
                </div>
                <div class="col">
                    <div class="col q-mb-sm">
                        <h1 class="text-h4 q-mt-none q-mb-sm">
                            {{
                                person.name.en ? person.name.en : person.name.jp
                            }}
                            <q-badge
                                class="text-subtitle1"
                                align="middle"
                                color="grey-7"
                            >
                                {{ person.movies.length }} Movies
                            </q-badge>
                        </h1>
                        <span v-if="person.name.en" class="text-subtitle1">{{
                            person.name.jp
                        }}</span>
                    </div>
                    <div class="row justify-start items-start">
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>Birthdate:</strong><br />
                                <span>{{ person.birthdate ?? "Unknown" }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Active:</strong><br />
                                <span v-if="person.career_start"
                                    >{{ person.career_start }} -
                                    {{ person.career_end ?? "Present" }}</span
                                >
                                <span v-else>Unknown</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Country:</strong><br />
                                <span>{{ person.country ?? "Unknown" }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Blood Type:</strong><br />
                                <span>{{
                                    person.blood_type ?? "Unknown"
                                }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>Height:</strong><br />
                                <span>{{
                                    person.height
                                        ? `${person.height}cm`
                                        : "Unknown"
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Bust:</strong><br />
                                <span>{{
                                    person.bust ? `${person.bust}cm` : "Unknown"
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Waist:</strong><br />
                                <span>{{
                                    person.waist
                                        ? `${person.waist}cm`
                                        : "Unknown"
                                }}</span>
                            </div>
                            <div class="q-mb-md">
                                <strong>Hips:</strong><br />
                                <span>{{
                                    person.hip ? `${person.hip}cm` : "Unknown"
                                }}</span>
                            </div>
                        </div>
                        <div class="col">
                            <div class="q-mb-md">
                                <strong>Cup Size:</strong><br />
                                <span
                                    >{{ person.cup_size ?? "Unknown" }}
                                    <template
                                        v-if="person.breast_implants !== null"
                                        >({{
                                            person.breast_implants
                                                ? "Implants"
                                                : "Natural"
                                        }})</template
                                    ></span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="q-pa-md">
            <div
                class="fit row wrap justify-start items-start content-start q-gutter-md"
            >
                <Card
                    v-for="movie in person.movies"
                    :key="movie.id"
                    :movie="movie"
                />
            </div>
        </div>
    </AppLayout>
</template>
