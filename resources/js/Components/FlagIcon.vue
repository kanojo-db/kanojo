<script setup lang="ts">
import type { Country } from '@/types/models';
import type { PropType } from 'vue';

import { computedAsync } from '@vueuse/core';

import MdiHelp from '~icons/mdi/help';

import { getCountryFlag } from '@/utils/item';

const props = defineProps({
    country: {
        type: Object as PropType<Country | null>,
        required: true,
    },
});

defineOptions({
    inheritAttrs: false,
});

const flagComponent = computedAsync(async () => {
    if (props.country === null) {
        return MdiHelp;
    }

    return await getCountryFlag(props.country);
}, MdiHelp);
</script>

<template>
    <v-tooltip
        location="bottom"
        :text="country ? country.name : 'Unknown'"
    >
        <template #activator="{ props: tooltipProps }">
            <v-icon
                v-bind="tooltipProps"
                :icon="flagComponent"
                :class="$attrs.class"
            />
        </template>
    </v-tooltip>
</template>
