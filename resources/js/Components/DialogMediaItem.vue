<script setup lang="ts">
import type { Media } from '@/types/models';
import type { PropType } from 'vue';

const props = defineProps({
    media: {
        type: Object as PropType<Media>,
        required: true,
    },
});
</script>

<template>
    <v-card
        max-width="90%"
        max-height="90%"
    >
        <img
            :src="props.media.original_url"
            :alt="props.media.name"
        />
    </v-card>
</template>
