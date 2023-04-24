<script setup lang="ts">
import type { User } from '@/types/models';
import type { PropType } from 'vue';

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    size: {
        type: [String, Number] as PropType<
            'x-small' | 'small' | 'default' | 'large' | 'x-large' | number
        >,
        default: 'default',
    },
    textClass: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <v-avatar
        :size="props.size"
        color="primary"
    >
        <v-img
            v-if="props.user.has_gravatar"
            :src="props.user.gravatar_url"
            class="object-cover"
            :alt="`Profile picture of ${props.user.name}`"
            loading="lazy"
        />

        <span
            v-else
            class="font-black"
            :class="props.textClass"
        >
            {{ props.user.name[0].toUpperCase() }}
        </span>
    </v-avatar>
</template>
