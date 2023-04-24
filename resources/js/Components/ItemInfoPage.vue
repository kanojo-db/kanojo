<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import chroma from 'chroma-js';
import { computed, provide } from 'vue';
import { useTheme } from 'vuetify';

import ItemTabBar from '@/Components/ItemTabBar.vue';
import { isMovie, isPerson, isSeries, isStudio } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    headerColor: {
        type: String,
        default: null,
    },
});

const theme = useTheme();

const isTextWhite = computed(() => {
    if (!props.headerColor) {
        if (theme.global.current.value.dark) {
            return true;
        }

        return false;
    }

    // We compute the contrast for the color of the tab bar, to ensure that the
    // text color is the same in both components. This avoids things looking
    // weird in a few edge cases.
    const tabColors = chroma(props.headerColor).darken(0.3);

    const whiteContrast = chroma.contrast(tabColors, 'white');
    const blackContrast = chroma.contrast(tabColors, 'black');

    return whiteContrast > blackContrast;
});

provide('isTextWhite', isTextWhite);

const schemaType = computed(() => {
    if (isMovie(props.item)) {
        return 'https://schema.org/Movie';
    } else if (isStudio(props.item)) {
        return 'https://schema.org/Corporation';
    } else if (isPerson(props.item)) {
        return 'https://schema.org/Person';
    } else if (isSeries(props.item)) {
        return 'https://schema.org/MovieSeries';
    }

    return undefined;
});
</script>

<template>
    <div
        itemscope
        :itemtype="schemaType"
        class="h-fit"
    >
        <div
            class="p-0"
            :class="{
                'bg-stone-300 dark:bg-stone-700': !headerColor,
                'text-stone-50': isTextWhite,
                'text-stone-950': !isTextWhite,
            }"
            :style="{
                'background-color': headerColor,
            }"
        >
            <item-tab-bar
                :item="item"
                :color="headerColor"
            />

            <slot name="header" />
        </div>

        <slot />
    </div>
</template>
