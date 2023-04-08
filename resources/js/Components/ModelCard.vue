<script setup>
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

import { useFirstImage, useName } from '@/utils/item';

const props = defineProps({
    model: {
        type: Object,
        required: true,
    },
});

const posterUrl = useFirstImage(props.model, 'profile');

const locale = useI18n().locale.value;

const name = useName(props.model, locale);
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
                class="rounded-borders shadow-1"
            />
            <div
                v-else
                class="row bg-grey-1 rounded-borders justify-center items-center shadow-1"
                style="width: 200px; height: 300px"
            >
                <q-icon
                    name="mdi-help"
                    size="150px"
                    color="grey-2"
                />
            </div>
            <div
                class="fit column no-wrap justify-start items-start content-start relative-position q-pt-sm"
            >
                <span class="text-weight-bold q-mt-sm">{{ name }}</span>
            </div>
        </div>
    </Link>
</template>
