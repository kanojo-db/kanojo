<script setup lang="ts">
import { getMenuEntries } from '@/utils/misc';

const menuEntries = getMenuEntries();
</script>

<template>
    <v-list>
        <template
            v-for="(menuEntry, index) in menuEntries"
            :key="`nav-menu-${index}`"
        >
            <v-list-subheader>{{ menuEntry.title }}</v-list-subheader>

            <v-list-item
                v-for="(subMenuItem, subIndex) in menuEntry.items"
                :key="`nav-menu-${index}-sub-${subIndex}`"
                :to="route(subMenuItem.routeName, subMenuItem.routeParams)"
            >
                {{ subMenuItem.title }}
            </v-list-item>
        </template>
    </v-list>
</template>
