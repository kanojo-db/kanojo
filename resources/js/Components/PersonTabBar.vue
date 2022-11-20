<script setup>
defineProps({
    person: Object,
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
                @click="$inertia.get(route('models.show', person))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/Media',
                }"
                label="Media"
                @click="$inertia.get(route('models.media.index', person))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/History',
                }"
                label="History"
                @click="$inertia.get(route('models.history.index', person))"
            />
            <q-btn
                flat
                :class="{
                    'text-weight-bold': $page.component === 'Model/Edit',
                }"
                label="Edit"
                @click="$inertia.get(route('models.edit', person))"
            />
        </q-btn-group>
    </div>
</template>
