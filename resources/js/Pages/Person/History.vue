<script setup>
import { Link } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { useI18n } from 'vue-i18n';

import PersonTabBar from '@/Components/PersonTabBar.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { useFirstImage, useName } from '@/utils/item';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    model: {
        type: Object,
        required: true,
    },
    history: {
        type: Array,
        required: true,
    },
});

const posterUrl = useFirstImage(props.model, 'profile');

const locale = useI18n().locale.value;

const name = useName(props.model, locale);

const isSystemChange = (change) => {
    return (
        (change.user_id === null && change.url === 'console') ||
        (change.user_id === null && change.user_type === null)
    );
};

const getChangeIcon = (change) => {
    switch (change.event) {
        case 'created':
            return 'mdi-auto-fix';
        case 'updated':
            return 'mdi-pencil';
        default:
            return 'mdi-help';
    }
};
</script>

<template>
    <div class="col bg-grey-3">
        <PersonTabBar :person="props.model" />
        <div class="row q-py-md q-px-md">
            <div
                class="q-pl-none q-mr-lg"
                style="max-width: 300px"
            >
                <q-img
                    v-if="posterUrl"
                    :src="posterUrl"
                    width="80px"
                    :ratio="2 / 3"
                    fit="cover"
                    class="rounded-borders"
                />
                <div
                    v-else
                    class="row bg-grey-1 rounded-borders justify-center items-center"
                    style="width: 80px; height: 120px"
                >
                    <q-icon
                        name="mdi-help"
                        size="60px"
                        color="grey-2"
                    />
                </div>
            </div>
            <div class="col flex">
                <div
                    class="column full-height justify-start items-start q-mb-sm"
                >
                    <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                        {{ name }}
                    </h1>
                    <Link
                        :href="route('movies.show', props.model)"
                        class="text-subtitle1"
                    >
                        <q-icon
                            name="mdi-arrow-left"
                            size="14px"
                        />
                        Back to Overview
                    </Link>
                </div>
            </div>
        </div>
    </div>
    <div class="q-mx-md">
        <div class="row q-col-gutter-md">
            <h2 class="text-h5 q-mb-none">
                {{ props.history.length }} Changes
            </h2>
            <div
                v-for="(change, index) in props.history"
                :key="`change-${index}`"
                class="full-width"
            >
                <q-card
                    flat
                    bordered
                >
                    <q-item class="bg-grey-2 justify-start items-center">
                        <q-avatar
                            size="32px"
                            font-size="16px"
                            color="white"
                            :text-color="
                                isSystemChange(change) ? 'grey-6' : 'grey-1'
                            "
                            :icon="
                                isSystemChange(change)
                                    ? 'mdi-server'
                                    : 'mdi-help'
                            "
                        />
                        <span class="text-weight-bold q-ml-sm">
                            {{
                                isSystemChange(change)
                                    ? 'System'
                                    : change.user?.name
                            }}
                        </span>
                    </q-item>

                    <q-separator />

                    <q-item class="justify-start items-center">
                        <q-icon
                            size="16px"
                            color="'grey-6'"
                            :name="getChangeIcon(change)"
                        />
                        <span class="text-weight-bold q-ml-sm">
                            {{ change.event }} on
                            {{
                                DateTime.fromISO(
                                    change.created_at,
                                ).toLocaleString(
                                    DateTime.DATETIME_FULL_WITH_SECONDS,
                                )
                            }}
                        </span>
                    </q-item>

                    <q-separator />

                    <q-item
                        v-if="change.old_values"
                        class="bg-red-1 justify-start items-center"
                    >
                        <div class="q-mr-md">
                            <q-icon
                                size="16px"
                                color="'grey-6'"
                                name="mdi-minus"
                            />
                        </div>
                        <div>
                            {{ JSON.stringify(change.old_values) }}
                        </div>
                    </q-item>

                    <q-separator />

                    <q-item
                        v-if="change.new_values"
                        class="bg-green-1 justify-start items-center"
                    >
                        <div class="q-mr-md">
                            <q-icon
                                size="16px"
                                color="'grey-6'"
                                name="mdi-plus"
                            />
                        </div>
                        <div>
                            {{ JSON.stringify(change.new_values) }}
                        </div>
                    </q-item>
                </q-card>
            </div>
        </div>
    </div>
</template>
