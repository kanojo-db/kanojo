<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    policy: {
        type: String,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Privacy Policy" />

    <div class="font-sans text-gray-900 antialiased">
        <div class="bg-gray-100 pt-4">
            <div class="flex min-h-screen flex-col items-center pt-6 sm:pt-0">
                <div
                    class="prose mt-6 w-full overflow-hidden bg-stone-50 p-6 shadow-md sm:max-w-2xl sm:rounded-lg"
                    v-html="policy"
                />
            </div>
        </div>
    </div>
</template>
