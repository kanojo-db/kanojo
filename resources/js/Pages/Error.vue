<script setup>
import { computed } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({ status: Number });

const title = computed(() => {
    return {
        503: '503: Service Unavailable',
        500: '500: Server Error',
        404: '404: Page Not Found',
        403: '403: Forbidden',
    }[props.status ?? 500];
});

const description = computed(() => {
    return {
        503: 'Sorry, we are doing some maintenance. Please check back soon.',
        500: 'Whoops, something went wrong on our servers.',
        404: 'Sorry, the page you are looking for could not be found.',
        403: 'Sorry, you are forbidden from accessing this page.',
    }[props.status ?? 500];
});
</script>

<template>
    <div class="row q-ma-xl">
        <h1 class="q-mt-none text-h1 text-weight-bold">{{ title }}</h1>
        <div class="q-mb-none text-h3 text-weight-regular">
            {{ description }}
        </div>
    </div>
</template>
