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
                @click="$inertia.get(route('movies.show', props.movie))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Movie/Media',
                }"
                label="Media"
                @click="$inertia.get(route('movies.show', props.movie))"
            />
            <q-btn
                flat
                label="History"
                @click="
                    $inertia.get(route('movies.history.index', props.movie))
                "
            />
            <q-btn
                flat
                label="Edit"
                @click="$inertia.get(route('movies.edit', props.movie))"
            />
        </q-btn-group>
    </div>
</template>
