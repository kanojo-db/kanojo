<script setup lang="ts">
import { computed } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({ status: Number });

const title = computed(() => {
    return {
        503: 'Oops! Service Unavailable',
        500: 'Oops! Internal Server Error',
        404: 'Oops! Page Not Found',
        403: 'Oops! Access Denied',
    }[props.status ?? 500];
});

const description = computed(() => {
    return {
        503: "We're sorry, but our service is temporarily unavailable. We're working diligently to resolve the issue and get things back up and running as soon as possible. Please try again later. Thank you for your patience!",
        500: "We're sorry, but an internal server error occurred. Our team has been notified and is diligently working to resolve the issue. Please bear with us while we fix the problem as quickly as possible. Thank you for your understanding and patience!",
        404: "We're sorry, but the page you are looking for could not be found. Please check the URL or try navigating to a different page. If you believe this is an error, please contact our support team for assistance. Thank you!",
        403: "We're sorry, but you do not have permission to access the requested resource. If you believe this is an error, please contact our support team for assistance. Thank you for your understanding and patience.",
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
