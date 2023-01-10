<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

import MenuCardSettings from '@/Components/MenuCardSettings.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    settings: {
        type: Object,
        required: true,
    },
});

const accountSettingsFoirm = useForm({
    show_jav: props.settings.show_jav.value,
    show_vr: props.settings.show_vr.value,
    show_gravure: props.settings.show_gravure.value,
    show_minors: props.settings.show_minors.value,
});

function submit() {
    accountSettingsFoirm.post(route('settings.account.update'));
}
</script>

<template>
    <AppLayout
        :title="`${$page.props.user.name} - ${$t(
            'web.settings.account.title',
        )}`"
    >
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
                    <MenuCardSettings />
                </div>
                <div class="col col-10">
                    <h2 class="text-h5 text-weight-bold q-mt-none q-mb-md">
                        {{ $t('web.settings.account.title') }}
                    </h2>
                    <!--<q-banner
                        v-if="$page.props.jetstream.flash.banner"
                        class="text-white q-mb-sm"
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
                    <q-form @submit.prevent="submit">
                        <div class="column items-start">
                            <div class="row items-center">
                                <h3
                                    class="text-h6 text-weight-bold q-mt-none q-mb-sm"
                                >
                                    {{
                                        $t(
                                            'web.settings.account.content_visibility',
                                        )
                                    }}
                                </h3>
                                <q-btn
                                    class="q-mb-sm q-ml-sm"
                                    round
                                    icon="mdi-help"
                                    size="xs"
                                    color="primary"
                                >
                                    <q-tooltip>
                                        {{
                                            $t(
                                                'web.settings.account.content_visibility_tooltip',
                                            )
                                        }}
                                    </q-tooltip>
                                </q-btn>
                            </div>
                            <q-toggle
                                v-model="accountSettingsFoirm.show_jav"
                                :label="
                                    $t(
                                        'web.settings.account.show_adult_content',
                                    )
                                "
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_vr"
                                :label="
                                    $t('web.settings.account.show_vr_content')
                                "
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_gravure"
                                :label="
                                    $t(
                                        'web.settings.account.show_gravure_content',
                                    )
                                "
                                color="primary"
                            />
                            <q-toggle
                                v-model="accountSettingsFoirm.show_minors"
                                :label="
                                    $t(
                                        'web.settings.account.show_gravure_minors_content',
                                    )
                                "
                                color="primary"
                            />
                            <q-btn
                                type="submit"
                                color="primary"
                                :label="$t('web.general.save_changes')"
                                class="q-mt-lg"
                            />
                        </div>
                    </q-form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
