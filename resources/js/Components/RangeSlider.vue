<script setup lang="ts">
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    LinearScale,
} from 'chart.js';
import { PropType, computed, ref } from 'vue';
import { Bar } from 'vue-chartjs';

import { Counts } from '@/types/internal';

ChartJS.register(BarElement, CategoryScale, LinearScale);

const props = defineProps({
    modelValue: {
        type: Object,
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
});

const emit = defineEmits(['update:modelValue']);

const value = ref({
    min: props.modelValue.min,
    max: props.modelValue.max,
});

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
        if (d >= value.value.min && d <= value.value.max) {
            return '#69306D';
        }

        return '#babfbc';
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

function updateValue($event: { min: number; max: number }) {
    value.value = $event;
    emit('update:modelValue', $event);
}
</script>

<template>
    <Bar
        class="fit"
        :data="chartData"
        :options="chartOptions"
        :width="345"
        :height="100"
    />
    <q-range
        :model-value="modelValue"
        :min="start"
        :max="end"
        :left-label-value="
            modelValue.min === null
                ? 'Not set'
                : `${modelValue.min}${leftLabelValue}`
        "
        :right-label-value="
            modelValue.max === null
                ? 'Not set'
                : `${modelValue.max}${rightLabelValue}`
        "
        snap
        label-always
        switch-label-side
        @update:model-value="updateValue"
    />
</template>
