<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { computed } from 'vue';

import SideMenu from '@/Components/SideMenu.vue';
import { EditSubroute } from '@/types/enums';
import { isMovie, isPerson, isSeries, isStudio } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
    modelValue: {
        type: String as PropType<EditSubroute>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', tab: EditSubroute): void;
}>();

function updateCurrentTab(tab: EditSubroute) {
    emit('update:modelValue', tab);
}

const menuEntries = computed(() => {
    if (isMovie(props.item)) {
        return [
            {
                value: EditSubroute.PrimaryFacts,
                label: 'primaryFacts',
            },
            {
                value: EditSubroute.Cast,
                label: 'cast',
            },
            {
                value: EditSubroute.Versions,
                label: 'versions',
            },
            /*{
                value: EditSubroute.Scenes,
                label: 'scenes',
            },*/
            {
                value: EditSubroute.ExternalIds,
                label: 'externalIds',
            },
        ];
    }

    if (isPerson(props.item)) {
        return [
            {
                value: EditSubroute.PrimaryFacts,
                label: 'primaryFacts',
            },
            {
                value: EditSubroute.AlternativeNames,
                label: 'alternativeNames',
            },
            {
                value: EditSubroute.ExternalIds,
                label: 'externalIds',
            },
        ];
    }

    if (isStudio(props.item)) {
        return [
            {
                value: EditSubroute.PrimaryFacts,
                label: 'primaryFacts',
            },
            {
                value: EditSubroute.ExternalIds,
                label: 'externalIds',
            },
        ];
    }

    if (isSeries(props.item)) {
        return [
            {
                value: EditSubroute.PrimaryFacts,
                label: 'primaryFacts',
            },
        ];
    }

    return [
        {
            value: 'primary_facts',
            label: 'Primary Facts',
        },
    ];
});
</script>

<template>
    <side-menu title="Edit">
        <v-item-group
            :model-value="props.modelValue"
            mandatory
            @update:model-value="updateCurrentTab"
        >
            <v-item
                v-for="entry in menuEntries"
                :key="entry.value"
                v-slot="{ isSelected, toggle }"
                :value="entry.value"
            >
                <v-list-item
                    :active="isSelected"
                    link
                    @click="toggle"
                >
                    {{ $t(`edit.sections.${entry.label}`) }}
                </v-list-item>
            </v-item>
        </v-item-group>
    </side-menu>
</template>
