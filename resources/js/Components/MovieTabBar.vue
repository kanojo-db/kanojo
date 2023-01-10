<script setup>
const props = defineProps({
    movie: {
        type: Object,
        required: true,
    },
});
</script>

<template>
    <div class="row bg-grey-4 q-py-sm justify-center items-center">
        <q-btn-group flat>
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Movie/Show',
                }"
                label="Overview"
                @click="
                    $inertia.get(
                        route('movies.show', { movie: props.movie.slug }),
                    )
                "
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Movie/Media',
                }"
                label="Media"
                @click="
                    $inertia.get(
                        route('movies.media.index', {
                            movie: props.movie.slug,
                        }),
                    )
                "
            />
            <q-btn
                flat
                label="History"
                @click="
                    $inertia.get(
                        route('movies.history.index', {
                            movie: props.movie.slug,
                        }),
                    )
                "
            />
            <q-btn
                flat
                label="Edit"
                @click="
                    $inertia.get(
                        route('movies.edit', { movie: props.movie.slug }),
                    )
                "
            />
        </q-btn-group>
    </div>
</template>
