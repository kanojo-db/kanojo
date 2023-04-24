<script setup lang="ts">
import type { Movies } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import { getTitle } from '@/utils/item';

const props = defineProps({
    movies: {
        type: Array as PropType<Movies>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const moviesPerYear = computed(() => {
    const groupedMovies = props.movies.reduce(
        (previousValue, currentValue) => {
            if (!currentValue.release_date) {
                return previousValue['Unknown'].push(currentValue);
            }

            const year = DateTime.fromISO(
                currentValue.release_date,
            ).year.toString();

            if (!previousValue[year]) {
                previousValue[year] = [];
            }

            previousValue[year].push(currentValue);

            return previousValue;
        },
        {} as Record<string, Movies>,
    );

    // Ensure the years are sorted in descending order, with Unknown at the end
    const sortedYears = Object.keys(groupedMovies).sort((a, b) => {
        if (a === 'Unknown') {
            return 1;
        }

        if (b === 'Unknown') {
            return -1;
        }

        return parseInt(b) - parseInt(a);
    });

    return sortedYears.map((year) => ({
        year,
        movies: groupedMovies[year],
    }));
});
</script>

<template>
    <div>
        <v-timeline
            align="start"
            side="end"
        >
            <v-timeline-item
                v-for="(value, key) in moviesPerYear"
                :key="key"
            >
                <template #opposite>
                    <h3 class="text-lg font-bold">{{ value.year }}</h3>
                </template>

                <v-card
                    v-for="movie in value.movies"
                    :key="movie.id"
                    class="max-w-2xl"
                >
                    <v-card-text>
                        <Link
                            :href="route('movies.show', { movie: movie.slug })"
                        >
                            {{ getTitle(movie, locale) }}
                        </Link>
                    </v-card-text>
                </v-card>
            </v-timeline-item>
        </v-timeline>
    </div>
</template>
