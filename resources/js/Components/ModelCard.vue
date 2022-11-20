<script setup>
import { computed } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

const { model } = defineProps({
    model: Object,
});

const posterUrl = computed(() => {
    return model?.media?.filter((m) => m.collection_name === "profile")?.[0]
        ?.original_url;
});
</script>

<template>
    <Link
        class="block"
        :href="route('models.show', model)"
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
                <span class="text-weight-bold q-mt-sm">{{
                    model.name.en ? model.name.en : model.name.jp
                }}</span>
                <!--<span class="q-mb-sm">{{ model.product_code }}</span>-->
            </div>
        </div>
    </Link>
</template>
