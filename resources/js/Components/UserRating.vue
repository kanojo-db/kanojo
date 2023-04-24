<script setup lang="ts">
import type { Reactable } from '@/types/models';
import type { PropType } from 'vue';

import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { useTheme } from 'vuetify';

const props = defineProps({
    reactable: {
        type: Object as PropType<Reactable>,
        required: true,
    },
    size: {
        type: Number,
        default: 10,
    },
    width: {
        type: Number,
        default: 2,
    },
    dark: {
        type: Boolean,
    },
});

const { t } = useI18n();

const theme = useTheme();

const isDark = computed(() => {
    if (props.dark !== undefined) {
        return props.dark;
    }

    return theme.global.current.value.dark;
});

const averageScore = computed(() => {
    if (props.reactable.love_reactant?.reaction_total?.count) {
        const likeReactions = props.reactable.love_reactant?.reactions?.filter(
            (reaction) => reaction.type.name === 'Like',
        );

        return (
            (likeReactions.length /
                props.reactable.love_reactant?.reaction_total?.count) *
            100
        );
    }

    return 0;
});

const userScore = computed(() => {
    if (props.reactable.love_reactant?.reaction_total?.count) {
        return averageScore.value;
    }

    return t('general.not_rated');
});
</script>

<template>
    <v-progress-circular
        :model-value="averageScore"
        :size="props.size"
        :width="props.width"
        :color="isDark ? 'primary' : 'secondary'"
    >
        <span
            class="font-black"
            :class="[
                isDark ? 'text-stone-50' : 'text-stone-800',
                isDark === undefined ? 'text-stone-800 dark:text-stone-50' : '',
            ]"
        >
            <i18n-t
                v-if="averageScore > 0"
                keypath="general.x_percent"
            >
                <template #number>
                    {{ userScore }}
                </template>
            </i18n-t>

            <template v-else>
                {{ $t('general.not_rated') }}
            </template>
        </span>
    </v-progress-circular>
</template>
