<script setup lang="ts">
import type { ChartData } from 'chart.js';
import type { PropType } from 'vue';

import {
    CategoryScale,
    Chart as ChartJS,
    Filler,
    LinearScale,
    LineElement,
    PointElement,
    Tooltip,
} from 'chart.js';
import chroma from 'chroma-js';
import { DateTime, Interval } from 'luxon';
import { computed } from 'vue';
import { Line } from 'vue-chartjs';
import { useI18n } from 'vue-i18n';
import { useTheme } from 'vuetify';

interface HistoryCount {
    [key: string]: number;
}

const props = defineProps({
    counts: {
        type: Object as PropType<Record<string, HistoryCount>>,
        required: true,
    },
});

ChartJS.register(
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
    Filler,
    Tooltip,
);

const theme = useTheme();

const { t } = useI18n();

function* days(interval: Interval) {
    if (!interval.start || !interval.end) {
        return;
    }

    let cursor = interval.start.startOf('day');
    while (cursor < interval.end) {
        yield cursor;
        cursor = cursor.plus({ days: 1 });
    }
}

const values = computed(() => {
    const min = DateTime.now().minus({ days: 30 });
    const max = DateTime.now();

    const interval = Interval.fromDateTimes(min, max);

    return Array.from(days(interval)).map((day) => day.toISODate());
});

const moviesCounts = computed(() => {
    return props.counts?.['App\\Models\\Movie'] ?? {};
});

const peopleCounts = computed(() => {
    return props.counts?.['App\\Models\\Person'] ?? [];
});

const studiosCounts = computed(() => {
    return props.counts?.['App\\Models\\Studio'] ?? [];
});

const seriesCounts = computed(() => {
    return props.counts?.['App\\Models\\Series'] ?? [];
});

const moviesCountsByValue = computed(() => {
    return values.value.map((value) => {
        if (props.counts === undefined || value === null) {
            return 0;
        }

        return moviesCounts.value?.[value] ?? 0;
    });
});

const personCountsByValue = computed(() => {
    return values.value.map((value) => {
        if (props.counts === undefined || value === null) {
            return 0;
        }

        return peopleCounts.value?.[value] ?? 0;
    });
});

const studioCountsByValue = computed(() => {
    return values.value.map((value) => {
        if (props.counts === undefined || value === null) {
            return 0;
        }

        return studiosCounts.value?.[value] ?? 0;
    });
});

const seriesCountsByValue = computed(() => {
    return values.value.map((value) => {
        if (props.counts === undefined || value === null) {
            return 0;
        }

        return seriesCounts.value?.[value] ?? 0;
    });
});

// TODO: Figure out how to type this.
// Hint: ChartData<"line", (number | Point | null)[], unknown>
const chartData = computed(() => {
    return {
        labels: values.value,
        datasets: [
            {
                data: moviesCountsByValue.value,
                borderColor: chroma(theme.current.value.colors.primary).alpha(
                    0.7,
                ),
                borderWidth: 1,
                backgroundColor: chroma(
                    theme.current.value.colors.primary,
                ).alpha(0.7),
                fill: true,
                tension: 0.4,
                pointStyle: 'circle',
                pointRadius: 1,
                pointBackgroundColor: theme.current.value.colors.primary,
                pointBorderColor: theme.current.value.colors.surface,
                pointBorderWidth: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: theme.current.value.colors.primary,
                pointHoverBorderColor: theme.current.value.colors.surface,
                pointHoverBorderWidth: 2,
            },
            {
                data: personCountsByValue.value,
                borderColor: chroma(theme.current.value.colors.secondary).alpha(
                    0.7,
                ),
                borderWidth: 1,
                backgroundColor: chroma(
                    theme.current.value.colors.secondary,
                ).alpha(0.7),
                fill: true,
                tension: 0.4,
                pointStyle: 'circle',
                pointRadius: 1,
                pointBackgroundColor: theme.current.value.colors.secondary,
                pointBorderColor: theme.current.value.colors.surface,
                pointBorderWidth: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: theme.current.value.colors.secondary,
                pointHoverBorderColor: theme.current.value.colors.surface,
                pointHoverBorderWidth: 2,
            },
            {
                data: studioCountsByValue.value,
                borderColor: chroma(theme.current.value.colors.tertiary).alpha(
                    0.7,
                ),
                borderWidth: 1,
                backgroundColor: chroma(
                    theme.current.value.colors.tertiary,
                ).alpha(0.7),
                fill: true,
                tension: 0.4,
                pointStyle: 'circle',
                pointRadius: 1,
                pointBackgroundColor: theme.current.value.colors.tertiary,
                pointBorderColor: theme.current.value.colors.surface,
                pointBorderWidth: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: theme.current.value.colors.tertiary,
                pointHoverBorderColor: theme.current.value.colors.surface,
                pointHoverBorderWidth: 2,
            },
            {
                data: seriesCountsByValue.value,
                borderColor: chroma(theme.current.value.colors.tertiary).alpha(
                    0.7,
                ),
                borderWidth: 1,
                backgroundColor: chroma(
                    theme.current.value.colors.tertiary,
                ).alpha(0.7),
                fill: true,
                tension: 0.4,
                pointStyle: 'circle',
                pointRadius: 1,
                pointBackgroundColor: theme.current.value.colors.tertiary,
                pointBorderColor: theme.current.value.colors.surface,
                pointBorderWidth: 0,
                pointHoverRadius: 5,
                pointHoverBackgroundColor: theme.current.value.colors.tertiary,
                pointHoverBorderColor: theme.current.value.colors.surface,
                pointHoverBorderWidth: 2,
            },
        ],
    };
});

const chartOptions = {
    responsive: true,
    maintainAspectRatio: true,
    aspectRatio: 6,
    interaction: {
        intersect: false,
        mode: 'nearest',
    },
    plugins: {
        filler: {
            propagate: false,
        },
        legend: {
            display: false,
        },
        tooltip: {
            enabled: true,
            displayColors: false,
            backgroundColor: theme.current.value.colors.surfaceDark,
            titleColor: theme.current.value.dark ? '#fff' : '#000',
            bodyColor: theme.current.value.dark ? '#fff' : '#000',
            padding: 8,
            callbacks: {
                title: function (context: { label: string }[]) {
                    return DateTime.fromISO(context[0].label).toLocaleString(
                        DateTime.DATE_FULL,
                    );
                },
                label: function (context: {
                    dataset: { data: { [x: string]: never } };
                    dataIndex: string | number;
                }) {
                    return t(
                        'profile.editsCount',
                        context.dataset.data[context.dataIndex] ?? 0,
                    );
                },
            },
        },
    },
    scales: {
        y: {
            display: true,
            beginAtZero: true,
        },
        x: {
            grid: {
                display: false,
            },
            display: true,
            ticks: {
                callback: function (value: number, index: number) {
                    return index % 5 ? '' : values.value[index];
                },
            },
        },
    },
};
</script>

<template>
    <Line
        :data="chartData"
        :options="chartOptions"
    />
</template>
