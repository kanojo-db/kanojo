<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useDisplay } from 'vuetify';

import MdiMagnify from '~icons/mdi/magnify';
import MdiMenu from '~icons/mdi/menu';
import MdiPlus from '~icons/mdi/plus';

import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import useRouteStore from '@/stores/route';
import { getMenuEntries } from '@/utils/misc';

import UserMenu from './UserMenu.vue';

const props = defineProps({
    showDrawer: {
        type: Boolean,
    },
});

const emit = defineEmits<{
    (event: 'update:showSearch', value: boolean): void;
    (event: 'update:showDrawer', value: boolean): void;
}>();

const menuEntries = getMenuEntries();

const routeStore = useRouteStore();

const searchParam = computed(() => routeStore.params?.q);

const search = ref('');

const submit = () => {
    router.get(
        route('search', {
            q: search.value,
        }),
    );
};

watch(
    () => searchParam,
    () => {
        search.value = searchParam.value ?? '';
    },
    { immediate: true },
);

const manuallyShowSearch = ref(false);
const showSearch = computed(() => !!searchParam.value);

const { mdAndUp } = useDisplay();
</script>

<template>
    <v-app-bar
        flat
        :extension-height="
            manuallyShowSearch || showSearch || $page.component === 'Search'
                ? 56
                : 0
        "
        class="bg-primary text-stone-50"
    >
        <v-app-bar-nav-icon
            class="block md:hidden"
            @click="() => emit('update:showDrawer', !props.showDrawer)"
        >
            <mdi-menu />
        </v-app-bar-nav-icon>

        <v-app-bar-title class="mr-2 max-w-[130px]">
            <Link :href="route('welcome')">
                <img
                    alt="Kanojo"
                    src="/images/logo-dark.svg"
                    class="h-[36px] w-[130px] object-contain"
                />
            </Link>
        </v-app-bar-title>

        <v-toolbar-items class="hidden w-fit gap-4 md:block">
            <v-menu
                v-for="(menuEntry, index) in menuEntries"
                :key="`nav-menu-${index}`"
                open-on-hover
            >
                <template #activator="{ props: menuProps }">
                    <v-btn
                        v-bind="menuProps"
                        variant="plain"
                        :text="menuEntry.title"
                    />
                </template>

                <v-list>
                    <v-list-item
                        v-for="(subMenuItem, subIndex) in menuEntry.items"
                        :key="`nav-menu-${index}-sub-${subIndex}`"
                        :to="
                            route(
                                subMenuItem.routeName,
                                subMenuItem.routeParams,
                            )
                        "
                    >
                        {{ subMenuItem.title }}
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-toolbar-items>

        <template #append>
            <div class="flex flex-row items-center gap-4">
                <v-menu
                    v-if="mdAndUp"
                    open-on-hover
                    offset="8px"
                >
                    <template #activator="{ props: menuProps }">
                        <v-btn
                            v-bind="menuProps"
                            rounded
                            variant="plain"
                            :icon="MdiPlus"
                            aria-label="Add"
                        />
                    </template>

                    <v-list>
                        <v-list-item :to="route('movies.create')">
                            {{ $t('general.addMovie') }}
                        </v-list-item>

                        <v-list-item :to="route('models.create')">
                            {{ $t('general.addModel') }}
                        </v-list-item>

                        <v-list-item :to="route('studios.create')">
                            {{ $t('general.addStudio') }}
                        </v-list-item>

                        <v-list-item :to="route('series.create')">
                            {{ $t('general.addSeries') }}
                        </v-list-item>
                    </v-list>
                </v-menu>

                <language-switcher />

                <user-menu />

                <v-btn
                    variant="plain"
                    :icon="MdiMagnify"
                    :aria-label="`Open search`"
                    @click="() => (manuallyShowSearch = !manuallyShowSearch)"
                />
            </div>
        </template>

        <template #extension>
            <v-text-field
                v-if="
                    manuallyShowSearch ||
                    showSearch ||
                    $page.component === 'Search'
                "
                v-model="search"
                :rounded="0"
                variant="solo"
                hide-details
                :placeholder="$t('general.search_placeholder')"
                @keyup.enter="submit"
            >
                <template #append-inner>
                    <v-btn
                        color="primary"
                        text="Search"
                        @click="submit"
                    />
                </template>
            </v-text-field>
        </template>
    </v-app-bar>
</template>
