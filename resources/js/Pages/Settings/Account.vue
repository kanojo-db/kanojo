<script setup lang="ts">
import type { User } from '@/types/models';

import { Head, useForm, usePage } from '@inertiajs/vue3';

import MdiHelpCircle from '~icons/mdi/help-circle';

import SideMenuPage from '@/Components/SideMenuPage.vue';
import SideMenuUser from '@/Components/SideMenuUser.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserName from '@/Components/UserName.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const page = usePage();

const accountSettingsForm = useForm({
    show_jav: props.settings.show_jav.value,
    show_vr: props.settings.show_vr.value,
    show_gravure: props.settings.show_gravure.value,
    show_minors: props.settings.show_minors.value,
});

function submit() {
    accountSettingsForm.post(route('settings.account.update'));
}
</script>

<template>
    <Head
        :title="`${page?.props?.user?.name} - ${$t('settings.account.title')}`"
    />

    <div class="flex flex-col bg-stone-300 dark:bg-stone-700">
        <v-container>
            <v-row align="center">
                <v-col>
                    <div
                        class="my-8 flex flex-col gap-6 lg:flex-row lg:items-center"
                    >
                        <user-avatar
                            :size="160"
                            :user="page.props.user as User"
                            text-class="text-8xl"
                        />

                        <div class="flex flex-col gap-6">
                            <div class="flex flex-row items-end">
                                <user-name
                                    class="my-0 text-ellipsis text-5xl font-black text-stone-700 dark:text-stone-100"
                                    :user="page.props.user as User"
                                />
                            </div>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
    </div>

    <side-menu-page>
        <template #left>
            <side-menu-user />
        </template>

        <template #right>
            <!--<q-banner
                        v-if="$page.props.jetstream.flash.banner"
                        class="text-stone-50 q-mb-sm"
                        :class="{
                            'bg-positive':
                                $page.props.jetstream.flash.bannerStyle ===
                                'success',
                            'bg-negative':
                                $page.props.jetstream.flash.bannerStyle ===
                                'error',
                        }"
                    >
                        {{ $page.props.jetstream.flash.banner }}
                    </q-banner>-->
            <v-form @submit.prevent="submit">
                <div class="flex flex-row gap-2">
                    <h3 class="text-xl font-bold">
                        {{ $t('settings.account.content_visibility') }}
                    </h3>

                    <v-tooltip
                        :text="
                            $t('settings.account.content_visibility_tooltip')
                        "
                    >
                        <template #activator="{ props: tooltipProps }">
                            <v-icon
                                v-bind="tooltipProps"
                                :icon="MdiHelpCircle"
                            />
                        </template>
                    </v-tooltip>
                </div>

                <v-switch
                    v-model="accountSettingsForm.show_jav"
                    :label="$t('settings.account.show_adult_content')"
                    hide-details
                />

                <v-switch
                    v-model="accountSettingsForm.show_gravure"
                    :label="$t('settings.account.show_gravure_content')"
                    hide-details
                />

                <v-switch
                    v-model="accountSettingsForm.show_minors"
                    :label="$t('settings.account.show_gravure_minors_content')"
                    hide-details
                />

                <v-btn
                    type="submit"
                    color="primary"
                    :text="$t('general.saveChanges')"
                    class="mt-2"
                />
            </v-form>
        </template>
    </side-menu-page>
</template>
