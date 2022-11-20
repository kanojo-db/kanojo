<script setup>
import { computed, ref } from "vue";
import { Bar } from "vue-chartjs";
import {
    Chart as ChartJS,
    BarElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(BarElement, CategoryScale, LinearScale);

const { modelValue, counts, leftLabelValue, rightLabelValue } = defineProps({
    modelValue: Object,
    counts: Object,
    leftLabelValue: {
        type: String,
        default: "",
    },
    rightLabelValue: {
        type: String,
        default: "",
    },
});

const emit = defineEmits(["update:modelValue"]);

const value = ref({
    min: modelValue.min,
    max: modelValue.max,
});

const values = computed(() => {
    const values = counts.map((c) => c.value);
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
            return "#69306D";
        }

        return "#babfbc";
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
    maintainAspectRatio: false,
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

function updateValue($event) {
    value.value = $event;
    emit("update:modelValue", $event);
}
</script>

<template>
    <Bar
        :chart-data="chartData"
        :chart-options="chartOptions"
        :width="345"
        :height="100"
    />
    <q-range
        :model-value="modelValue"
        :min="start"
        :max="end"
        :left-label-value="modelValue.min === null ? 'Not set' :`${modelValue.min}${leftLabelValue}`"
        :right-label-value="modelValue.max === null ? 'Not set' :`${modelValue.max}${rightLabelValue}`"
        snap
        label-always
        switch-label-side
        @update:modelValue="updateValue"
    />
</template>
