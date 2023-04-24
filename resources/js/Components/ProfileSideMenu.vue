<script setup lang="ts">
import type { PropType } from 'vue';

import { usePage } from '@inertiajs/vue3';

import SideMenu from '@/Components/SideMenu.vue';
import { ProfileSubroute } from '@/types/enums';

const props = defineProps({
    modelValue: {
        type: String as PropType<ProfileSubroute>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', tab: ProfileSubroute): void;
}>();

const page = usePage();

function updateCurrentTab(tab: ProfileSubroute) {
    emit('update:modelValue', tab);
}
</script>

<template>
    <side-menu title="Profile">
        <v-item-group
            :model-value="props.modelValue"
            mandatory
            @update:model-value="updateCurrentTab"
        >
            <v-item
                v-slot="{ isSelected, toggle }"
                :value="ProfileSubroute.Activity"
            >
                <v-list-item
                    :active="isSelected"
                    link
                    @click="toggle"
                >
                    {{ $t('profile.activity.title') }}
                </v-list-item>
            </v-item>

            <v-item
                v-slot="{ isSelected, toggle }"
                :value="ProfileSubroute.Collection"
            >
                <v-list-item
                    :active="isSelected"
                    link
                    @click="toggle"
                >
                    {{ $t('profile.collection') }}

                    <template #append>
                        <v-chip>
                            {{ page.props.collectionCount }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-item>

            <v-item
                v-slot="{ isSelected, toggle }"
                :value="ProfileSubroute.Favorites"
            >
                <v-list-item
                    :active="isSelected"
                    link
                    @click="toggle"
                >
                    {{ $t('profile.favorites') }}

                    <template #append>
                        <v-chip>
                            {{ page.props.favoriteCount }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-item>

            <v-item
                v-slot="{ isSelected, toggle }"
                :value="ProfileSubroute.Wishlist"
            >
                <v-list-item
                    :active="isSelected"
                    link
                    @click="toggle"
                >
                    {{ $t('profile.wishlist') }}

                    <template #append>
                        <v-chip>
                            {{ page.props.wishlistCount }}
                        </v-chip>
                    </template>
                </v-list-item>
            </v-item>
        </v-item-group>
    </side-menu>
</template>
