<script setup>
import { useForm } from "@inertiajs/inertia-vue3";

import AppLayout from "@/Layouts/AppLayout.vue";

const { settings } = defineProps({
    settings: Object,
});

const accountSettingsFoirm = useForm({
    show_jav: settings.show_jav.value,
    show_vr: settings.show_vr.value,
    show_gravure: settings.show_gravure.value,
    show_minors: settings.show_minors.value,
});

function submit() {
    accountSettingsFoirm.post(route("settings.account.update"));
}
</script>

<template>
    <AppLayout :title="`${$page.props.user.name} - Account Settings`">
        <div class="col bg-grey-3">
            <div class="row q-py-lg q-px-md">
                <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                    {{ $page.props.user.name }}
                </h1>
            </div>
        </div>
        <div class="q-ma-md">
            <div class="row q-col-gutter-lg full-width">
                <div class="col-2 q-pl-none">
                    <q-card flat bordered>
                        <q-card-section
                            class="bg-primary text-white row items-center"
                        >
                            <div class="text-weight-bold text-h6">Settings</div>
                        </q-card-section>

                        <q-separator />

                        <q-list>
                            <q-item
                                clickable
                                :class="{
                                    'text-weight-bold text-primary':
                                        $page.component === 'Settings/Account',
                                }"
                            >
                                <q-item-section>
                                    Account Settings
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-card>
                </div>
                <div class="col col-10">
                    <h2 class="text-h5 text-weight-bold q-mt-none q-mb-md">
                        Account Settings
                    </h2>
                    <q-banner
                        v-if="$page.props.jetstream.flash.banner"
                        class="text-white q-mb-sm"
                        :class="{
                            'bg-positive': $page.props.jetstream.flash.bannerStyle ===
                                'success',
                            'bg-negative': $page.props.jetstream.flash.bannerStyle ===
                                'error',
                        }"
                    >
                        {{ $page.props.jetstream.flash.banner }}
                    </q-banner>
                    <q-form @submit.prevent="accountSettingsFoirm.post('')">
                        <div class="column items-start">
                            <h3 class="text-h6 text-weight-bold q-mt-none q-mb-sm">
                                Content Visibility
                            </h3>
                            <q-toggle
                                v-model="accountSettingsFoirm.show_jav"
                                label="Show adult content"
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_vr"
                                label="Show VR content"
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_gravure"
                                label="Show gravure content"
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_minors"
                                label="Show gravure content featuring minors"
                                color="primary"
                            />
                            <q-btn type="submit" color="primary" label="Save" class="q-mt-lg" />
                        </div>
                    </q-form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
