<script setup lang="ts">
import type { User } from '@/types/models';

import { Head, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

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

const isDeleting = ref(false);

function deleteAccount() {
    if (page.props.user) {
        isDeleting.value = true;

        router.delete(route('users.destroy', { user: page.props.user.id }), {
            onFinish: () => {
                isDeleting.value = false;
            },
        });
    }
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
            <v-form @submit.prevent="submit">
                <v-row>
                    <v-col>
                        <div class="flex flex-row gap-2">
                            <h3 class="text-xl font-bold">
                                {{ $t('settings.account.content_visibility') }}
                            </h3>

                            <v-tooltip
                                :text="
                                    $t(
                                        'settings.account.content_visibility_tooltip',
                                    )
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
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-switch
                            v-model="accountSettingsForm.show_jav"
                            disabled
                            :label="$t('settings.account.show_adult_content')"
                            hide-details
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-switch
                            v-model="accountSettingsForm.show_gravure"
                            disabled
                            :label="$t('settings.account.show_gravure_content')"
                            hide-details
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-switch
                            v-model="accountSettingsForm.show_minors"
                            disabled
                            :label="
                                $t(
                                    'settings.account.show_gravure_minors_content',
                                )
                            "
                            hide-details
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-btn
                            type="submit"
                            color="primary"
                            disabled
                            :text="$t('general.saveChanges')"
                            class="mt-2"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-card
                            border
                            class="border-red-500"
                            variant="flat"
                        >
                            <v-card-title class="font-black">
                                {{ $t('settings.account.dangerZone') }}
                            </v-card-title>

                            <v-card-text
                                class="flex flex-col items-start gap-4"
                            >
                                <p
                                    class="prose"
                                    v-text="
                                        $t('settings.account.deleteAccountInfo')
                                    "
                                />

                                <v-btn
                                    color="red"
                                    :loading="isDeleting"
                                    @click="deleteAccount"
                                >
                                    {{ $t('settings.account.deleteAccount') }}
                                </v-btn>
                            </v-card-text>
                        </v-card>
                    </v-col>
                </v-row>
            </v-form>
        </template>
    </side-menu-page>
</template>
