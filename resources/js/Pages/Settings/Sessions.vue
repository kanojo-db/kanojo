<script setup>
import { DateTime } from 'luxon';

import MenuCardSettings from '@/Components/MenuCardSettings.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    sessions: {
        type: Array,
        required: true,
    },
});

const columns = [
    {
        name: 'ip_address',
        label: 'IP Address',
        field: 'ip_address',
        required: true,
        align: 'left',
    },
    {
        name: 'browser',
        label: 'Browser',
        field: 'browser',
        required: true,
        align: 'left',
    },
    {
        name: 'platform',
        label: 'Platform',
        field: 'platform',
        required: true,
        align: 'left',
    },
    {
        name: 'last_active',
        label: 'Last Active',
        field: 'last_active',
        required: true,
        align: 'left',
        format: (val) => {
            return DateTime.fromISO(val).toRelative();
        },
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
                        {{ $t('web.settings.sessions.title') }}
                    </h2>
                    <q-table
                        grid
                        :rows="props.sessions"
                        :columns="columns"
                        row-key="id"
                        hide-header
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
