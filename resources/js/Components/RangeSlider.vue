<script setup lang="ts">
import type { Counts } from '@/types/internal';
import type { PropType } from 'vue';

import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    LinearScale,
} from 'chart.js';
import { computed } from 'vue';
import { Bar } from 'vue-chartjs';
import { useTheme } from 'vuetify';

const props = defineProps({
    modelValue: {
        type: Object as PropType<(number | null)[]>,
        required: true,
    },
    counts: {
        type: Array as PropType<Counts>,
        required: true,
    },
    leftLabelValue: {
        type: String,
        default: '',
    },
    rightLabelValue: {
        type: String,
        default: '',
    },
    disabled: {
        type: Boolean,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: [number | null, number | null]): void;
}>();

const theme = useTheme();

ChartJS.register(BarElement, CategoryScale, LinearScale);

const values = computed(() => {
    const values = props.counts.map((c) => c.value);
    const min = Math.min(...values);
    const max = Math.max(...values);

    const range = Array.from({ length: max - min + 1 }, (_, i) => min + i);

    return range;
});

const start = computed(() => {
    return Math.min(...values.value);
});

const end = computed(() => {
    return Math.max(...values.value);
});

const chartColor = computed(() => {
    return values.value.map((d) => {
        if (props.modelValue[0] === null || props.modelValue[1] === null) {
            return theme.current.value.colors.darkSurface;
        }

        if (d >= props.modelValue[0] && d <= props.modelValue[1]) {
            return theme.current.value.colors.secondary;
        }

        return theme.current.value.colors.darkSurface;
    });
});

const chartData = computed(() => {
    // Build an array of count for each year
    const countsByValue = values.value.map((value) => {
        const count = props.counts.find((c) => c.value === value);

        return count ? count.count : 0;
    });

    return {
        labels: values.value,
        datasets: [
            {
                data: countsByValue,
                backgroundColor: chartColor.value,
            },
        ],
    };
});

const chartOptions = {
    responsive: false,
    maintainAspectRatio: true,
    animation: {
        duration: 0,
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: false,
        },
    },
    scales: {
        y: {
            display: false,
            beginAtZero: true,
        },
        x: {
            display: false,
        },
    },
};

function updateValue(event: [number | null, number | null]) {
    emit('update:modelValue', event);
}
</script>

<template>
    <bar
        class="w-full max-w-full"
        :data="chartData"
        :options="chartOptions"
        :width="345"
        :height="100"
    />

    <v-range-slider
        :model-value="props.modelValue"
        :min="start"
        :max="end"
        :step="1"
        strict
        hide-details
        track-fill-color="#6a306d"
        :disabled="props.disabled"
        @update:model-value="updateValue"
    />

    <div class="flex items-center justify-between">
        <span class="my-2 text-sm text-gray-500">{{
            props.modelValue[0] ?? $t('general.notSet')
        }}</span>

        <v-btn
            v-if="props.modelValue[0] !== null || props.modelValue[1] !== null"
            size="small"
            variant="text"
            @click="updateValue([null, null])"
        >
            {{ $t('general.clear') }}
        </v-btn>

        <span class="my-2 text-sm text-gray-500">{{
            props.modelValue[1] ?? $t('general.notSet')
        }}</span>
    </div>
</template>
