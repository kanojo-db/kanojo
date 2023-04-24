<script setup lang="ts">
import type { User } from '@/types/models';
import type { PropType } from 'vue';

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    is: {
        type: String,
        default: 'p',
    },
});

defineOptions({
    inheritAttrs: false,
});
</script>

<template>
    <div class="flex flex-row flex-wrap items-end gap-4">
        <component
            :is="props.is"
            v-bind="$attrs"
        >
            {{ props.user.name }}
        </component>

        <v-chip
            v-if="props.user.is_administrator"
            color="primary"
        >
            {{ $t('user.tags.admin') }}
        </v-chip>

        <v-chip
            v-if="props.user.is_banned"
            color="error"
        >
            {{ $t('user.tags.banned') }}
        </v-chip>
    </div>
</template>
