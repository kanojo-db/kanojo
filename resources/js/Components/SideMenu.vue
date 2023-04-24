<script setup lang="ts">
const props = defineProps({
    title: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <v-card class="h-fit">
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <slot name="header">
                <div class="text-lg font-bold">
                    {{ props.title }}
                </div>
            </slot>
        </v-card-title>

        <v-list>
            <slot />
        </v-list>
    </v-card>
</template>
