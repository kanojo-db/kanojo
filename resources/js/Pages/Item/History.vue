<script setup lang="ts">
import type { Audit, Item } from '@/types/models';
import type { PropType } from 'vue';

import { DateTime } from 'luxon';
import { useI18n } from 'vue-i18n';

import ItemInfoHeader from '@/Components/ItemInfoHeader.vue';
import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { isMovie, isSeries, useName, useTitle } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const { locale } = useI18n();

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const isSystemChange = (change: Audit) => {
    return change.user_id === null && change.url === 'console';
};

const getChangeIcon = (change: Audit) => {
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
    <Head :title="`Changes for ${title}`" />

    <item-info-page :item="props.item">
        <template #header>
            <item-info-header :item="item" />
        </template>

        <template #default>
            <v-container>
                <v-row
                    v-if="props.item.audits?.data?.length > 0"
                    class="row q-col-gutter-md"
                >
                    <v-col>
                        <h2 class="text-h5 q-mb-none">
                            {{ props.item.audits.length }} Changes
                        </h2>

                        <div
                            v-for="(change, index) in props.item.audits"
                            :key="`change-${index}`"
                            class="full-width"
                        >
                            <v-card>
                                <q-item
                                    class="bg-grey-2 items-center justify-items-start"
                                >
                                    <q-avatar
                                        size="32px"
                                        font-size="16px"
                                        color="white"
                                        :text-color="
                                            isSystemChange(change)
                                                ? 'grey-6'
                                                : 'grey-1'
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

                                <v-divider />

                                <q-item class="items-center justify-start">
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

                                <v-divider />

                                <q-item
                                    v-if="change.old_values"
                                    class="bg-red-1 items-center justify-items-start"
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

                                <v-divider />

                                <q-item
                                    v-if="change.new_values"
                                    class="bg-green-1 items-center justify-items-start"
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
                            </v-card>
                        </div>
                    </v-col>
                </v-row>

                <v-row v-else>
                    <v-col>
                        <div class="text-h5">No changes found.</div>
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </item-info-page>
</template>
