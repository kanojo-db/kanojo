<script setup>
const props = defineProps({
    person: {
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
                    'text-weight-bold': $page.component === 'Model/Show',
                }"
                label="Overview"
                @click="$inertia.get(route('models.show', props.person))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/Media',
                }"
                label="Media"
                @click="$inertia.get(route('models.media.index', props.person))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/History',
                }"
                label="History"
                @click="
                    $inertia.get(route('models.history.index', props.person))
                "
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/Edit',
                }"
                label="Edit"
                @click="$inertia.get(route('models.edit', props.person))"
            />
        </q-btn-group>
    </div>
</template>
