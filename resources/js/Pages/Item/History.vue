<script setup lang="ts">
import type { Audit, Item } from '@/types/models';
import type { PropType } from 'vue';

import { Head } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiAutoFix from '~icons/mdi/auto-fix';
import MdiHelp from '~icons/mdi/help';
import MdiPencil from '~icons/mdi/pencil';

import ItemInfoHeader from '@/Components/ItemInfoHeader.vue';
import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import Pagination from '@/Components/Pagination.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserName from '@/Components/UserName.vue';
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

const getChangeIcon = (change: Audit) => {
    switch (change.event) {
        case 'created':
            return MdiAutoFix;
        case 'updated':
            return MdiPencil;
        default:
            return MdiHelp;
    }
};

const currentPage = ref(props.item.audits?.current_page);

// Some items have JSON fields, so we need to clean them up before displaying
const jsonFields = ['title', 'name'];

function cleanChangelog(change: object) {
    // If change has any of the JSON fields, parse them and replace the value
    for (const field of jsonFields) {
        if (change[field]) {
            // If the value is already an object, skip it
            if (typeof change[field] === 'object') {
                continue;
            }

            change[field] = JSON.parse(change[field]);
        }
    }

    return JSON.stringify(change, null, 2);
}
</script>

<template>
    <Head :title="`Changes for ${title}`" />

    <item-info-page :item="props.item">
        <template #header>
            <item-info-header :item="item" />
        </template>

        <template #default>
            <v-container>
                <v-row v-if="props.item.audits?.data?.length > 0">
                    <v-col>
                        <h2 class="mb-2 text-lg font-bold">
                            {{
                                $t('history.numberOfChanges', {
                                    number: props.item?.audits.data.total,
                                })
                            }}
                        </h2>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.item.audits"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-card
                            v-for="(change, index) in props.item?.audits.data"
                            :key="`change-${index}`"
                        >
                            <v-card-title class="bg-stone-300 text-stone-950">
                                <div
                                    class="flex flex-row items-center justify-start gap-4"
                                >
                                    <user-avatar :user="change.user" />

                                    <user-name
                                        :user="change.user"
                                        class="text-base"
                                    />
                                </div>
                            </v-card-title>

                            <v-divider />

                            <div class="flex flex-row justify-start gap-2 p-2">
                                <v-icon
                                    class="h-6 w-6"
                                    :icon="getChangeIcon(change)"
                                />

                                <span>
                                    {{
                                        $t('history.actionOnDate', {
                                            action: $t(
                                                `history.events.${change.event}`,
                                            ),
                                            date: DateTime.fromISO(
                                                change.created_at,
                                            )
                                                .setLocale(locale)
                                                .toLocaleString(
                                                    DateTime.DATETIME_MED,
                                                ),
                                        })
                                    }}
                                </span>
                            </div>

                            <v-divider />

                            <pre
                                v-if="change.old_values"
                                class="bg-red-100 p-2 dark:bg-red-900"
                                v-text="cleanChangelog(change.old_values)"
                            />

                            <v-divider />

                            <pre
                                v-if="change.new_values"
                                class="bg-green-100 p-2 dark:bg-green-900"
                                v-text="cleanChangelog(change.new_values)"
                            />
                        </v-card>
                    </v-col>
                </v-row>

                <v-row v-if="props.item.audits.data.length === 0">
                    <v-col>
                        <div class="text-h5">
                            {{ $t('history.noChanges') }}
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.item.audits"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </item-info-page>
</template>
