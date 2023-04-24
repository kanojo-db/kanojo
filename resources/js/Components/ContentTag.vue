<script setup lang="ts">
import type { Tag } from '@/types/models';
import type { PropType } from 'vue';

import { useI18n } from 'vue-i18n';

import { useName } from '@/utils/item';

const props = defineProps({
    tag: {
        type: Object as PropType<Tag>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.tag, locale);
</script>

<template>
    <v-chip>{{ name }}</v-chip>
</template>
