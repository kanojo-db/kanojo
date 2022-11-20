<script setup>
import { computed } from "vue";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(BarElement, CategoryScale, LinearScale);

const { counts, customValues } = defineProps({
    counts: Object,
    customValues: Object,
});

const values = computed(() => {
    if (customValues) {
        return customValues;
    }

    const values = counts.map((c) => c.value);
    const min = Math.min(...values);
    const max = Math.max(...values);

    const range = Array.from({ length: max - min + 1 }, (_, i) => min + i);

    return range;
});

const chartColor = computed(() => {
    return values.value.map((d) => {
        return "#69306D";
    });
});

const chartData = computed(() => {
    // Build an array of count for each year
    const countsByValue = values.value.map((value) => {
        const count = counts.find((c) => c.value === value);

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
        :chart-data="chartData"
        :chart-options="chartOptions"
        :width="600"
        :height="300"
    />
</template>
