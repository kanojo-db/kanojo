<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const { movie } = defineProps({
    movie: Object,
});

const posterUrl = computed(() => {
    return movie?.media?.filter((m) => m.collection_name === "poster")?.[0]
        ?.original_url;
});
</script>

<template>
    <Link
        class="block"
        :href="route('movies.show', movie)"
        style="width: 200px"
    >
        <div class="fit column no-wrap justify-start items-start content-start">
            <q-img
                v-if="posterUrl"
                :src="posterUrl"
                width="200px"
                :ratio="2 / 3"
                fit="cover"
                class="rounded-borders"
            />
            <div v-else class="row bg-grey-1 rounded-borders justify-center items-center" style="width: 200px; height: 300px;">
                <q-icon name="mdi-help" size="150px" color="grey-2" />
            </div>
            <div class="fit column no-wrap justify-start items-start content-start relative-position q-pt-sm">
                <q-knob
                    readonly
                    :min="0"
                    :max="10"
                    :value="0"
                    show-value
                    size="50px"
                    :thickness="0.15"
                    color="primary"
                    center-color="grey-2"
                    track-color="grey-2"
                    class="absolute"
                    style="top: -25px"
                >
                    <div class="row justify-center items-start text-overline text-black">
                        <span class="text-weight-bolder">{{
                            0
                        }}</span
                        >%
                    </div>
                </q-knob>
                <span class="text-weight-bold q-mt-lg ellipsis-2-lines">{{
                    movie.title.en ? movie.title.en : movie.title.jp
                }}</span>
                <span class="q-mb-sm">{{ movie.product_code }}</span>
            </div>
        </div>
    </Link>
</template>
