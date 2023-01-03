<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import copy from 'copy-to-clipboard';
import { DateTime } from 'luxon';
import { useQuasar } from 'quasar';

import MenuCardSettings from '@/Components/MenuCardSettings.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

import DialogCreateToken from '../../Components/DialogCreateToken.vue';

const props = defineProps({
    tokens: {
        type: Array,
        required: true,
    },
});

const tokenCreateForm = useForm({
    name: '',
});

const tokenCreateFormSubmit = () => {
    tokenCreateForm.post(route('settings.tokens.create'), {
        preserveScroll: true,
        onSuccess: () => {
            tokenCreateForm.reset();
        },
    });
};

const $q = useQuasar();

const openTokenCreateDialog = () => {
    $q.dialog({
        component: DialogCreateToken,
        componentProps: {
            tokenForm: tokenCreateForm,
            onSubmit: tokenCreateFormSubmit,
        },
    });
};

const copyToken = (token) => {
    copy(token, {
        onCopy: () => {
            $q.notify({
                message: 'Token copied to clipboard',
                color: 'positive',
                icon: 'mdi-check',
            });
        },
    });
};

const columns = [
    {
        name: 'name',
        label: 'Name',
        field: 'name',
        align: 'left',
    },
    {
        name: 'last_used_at',
        label: 'Last Used',
        field: 'last_used_at',
        align: 'left',
        format: (val) => {
            if (!val) {
                return 'Never';
            }

            return DateTime.fromISO(val).toRelative();
        },
    },
    {
        name: 'created_at',
        label: 'Created',
        field: 'created_at',
        align: 'left',
        format: (val) =>
            DateTime.fromISO(val).toLocaleString(DateTime.DATETIME_SHORT),
    },
];
</script>

<template>
    <AppLayout :title="`${$page.props.user.name} - Sessions`">
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
                        {{ $t('web.settings.tokens.title') }}
                    </h2>
                    <q-banner
                        v-if="props.tokens.length === 0"
                        inline-actions
                        class="bg-grey-1"
                    >
                        {{ $t('web.settings.tokens.no_tokens') }}
                        <template v-slot:action>
                            <q-btn
                                flat
                                color="primary"
                                :label="$t('web.settings.tokens.create_token')"
                                @click="openTokenCreateDialog"
                            />
                        </template>
                    </q-banner>
                    <template v-else>
                        <q-banner
                            v-if="$page.props._session?._flash?.token"
                            inline-actions
                            class="bg-grey-1 q-mb-md"
                        >
                            {{ $t('web.settings.tokens.success') }}
                            <template v-slot:action>
                                <q-btn
                                    icon="mdi-content-copy"
                                    flat
                                    color="primary"
                                    :label="
                                        $t('web.settings.tokens.copy_token')
                                    "
                                    @click="
                                        () =>
                                            copyToken(
                                                $page.props._session._flash
                                                    .token,
                                            )
                                    "
                                />
                            </template>
                        </q-banner>

                        <q-table
                            :rows="props.tokens"
                            :columns="columns"
                            row-key="id"
                        />

                        <q-btn
                            color="primary"
                            :label="$t('web.settings.tokens.create_token')"
                            class="q-mt-md"
                            @click="openTokenCreateDialog"
                        />
                    </template>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
