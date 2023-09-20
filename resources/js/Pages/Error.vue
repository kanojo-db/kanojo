<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({ status: { type: Number, default: 500 } });

defineOptions({
    layout: AppLayout,
});

const title = computed(() => {
    return {
        500: 'Oops! Internal Server Error',
        404: 'Oops! Page Not Found',
        403: 'Oops! Access Denied',
    }[props.status ?? 500];
});

const description = computed(() => {
    return {
        500: "We're sorry, but an internal server error occurred. Our team has been notified and is diligently working to resolve the issue. Please bear with us while we fix the problem as quickly as possible. Thank you for your understanding and patience!",
        404: "We're sorry, but the page you are looking for could not be found. Please check the URL or try navigating to a different page. If you believe this is an error, please contact our support team for assistance. Thank you!",
        403: "We're sorry, but you do not have permission to access the requested resource. If you believe this is an error, please contact our support team for assistance. Thank you for your understanding and patience.",
    }[props.status ?? 500];
});
</script>

<template>
    <Head :title="title" />

    <v-container>
        <div class="prose">
            <h1 class="text-2xl font-bold">{{ title }}</h1>

            <p>
                {{ description }}
            </p>
        </div>
    </v-container>
</template>
