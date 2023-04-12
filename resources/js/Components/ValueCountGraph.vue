<script setup lang="ts">
import {
    BarElement,
    CategoryScale,
    Chart as ChartJS,
    LinearScale,
} from 'chart.js';
import { PropType, computed } from 'vue';
import { Bar } from 'vue-chartjs';

ChartJS.register(BarElement, CategoryScale, LinearScale);

interface Count {
    value: number;
    count: number;
}

type Counts = Count[];

const props = defineProps({
    counts: {
        type: Array as PropType<Counts>,
        required: true,
    },
    customValues: {
        type: Array,
        default: undefined,
    },
    highlightValue: {
        type: Number,
        default: undefined,
    },
});

const values = computed(() => {
    if (props.customValues) {
        return props.customValues;
    }

    if (props.counts === undefined) {
        return [];
    }

    const values = props.counts.map((c) => c.value);
    const min = Math.min(...values);
    const max = Math.max(...values);

    const range = Array.from({ length: max - min + 1 }, (_, i) => min + i);

    return range;
});

const chartColor = computed(() => {
    return values.value.map((value) => {
        if (value == props.highlightValue) {
            return '#BB4430';
        }

        return '#69306D';
    });
});

const chartData = computed(() => {
    // Build an array of count for each year
    const countsByValue = values.value.map((value) => {
        if (props.counts === undefined) {
            return 0;
        }

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
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 2,
    animation: {
        duration: 0,
    },
    plugins: {
        legend: {
            display: false,
        },
        tooltip: {
            enabled: true,
        },
    },
    scales: {
        y: {
            display: true,
            beginAtZero: true,
        },
        x: {
            display: true,
        },
    },
};
</script>

<template>
    <Bar
        :data="chartData"
        :options="chartOptions"
    />
</template>
