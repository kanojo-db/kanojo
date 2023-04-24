<script setup lang="ts">
import type { PageProps } from '@/types';

import { usePage } from '@inertiajs/vue3';

import SideMenu from '@/Components/SideMenu.vue';

const page = usePage<PageProps>();
</script>

<template>
    <side-menu :title="$t('general.pages.settings')">
        <v-list-item
            :active="page?.component === 'Settings/Account'"
            :to="route('settings.account')"
        >
            {{ $t('settings.account.title') }}
        </v-list-item>

        <v-list-item
            :active="page?.component === 'Settings/Tokens'"
            :to="route('settings.tokens')"
        >
            {{ $t('settings.tokens.title') }}
        </v-list-item>

        <v-list-item
            :active="page?.component === 'Settings/Sessions'"
            :to="route('settings.sessions')"
        >
            {{ $t('settings.sessions.title') }}
        </v-list-item>
    </side-menu>
</template>
