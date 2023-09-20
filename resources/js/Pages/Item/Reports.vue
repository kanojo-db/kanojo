<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import ItemInfoHeader from '@/Components/ItemInfoHeader.vue';
import ItemInfoPage from '@/Components/ItemInfoPage.vue';
import Pagination from '@/Components/Pagination.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserName from '@/Components/UserName.vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    getItemRouteName,
    getItemRouteParams,
    isMovie,
    isSeries,
    useName,
    useTitle,
} from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const { locale, t } = useI18n();

const reportStatus = [
    {
        name: t('dialogs.reportContent.open'),
        value: 'open',
    },
    {
        name: t('dialogs.reportContent.resolved'),
        value: 'resolved',
    },
    {
        name: t('dialogs.reportContent.rejected'),
        value: 'rejected',
    },
    {
        name: t('dialogs.reportContent.invalid'),
        value: 'invalid',
    },
    {
        name: t('dialogs.reportContent.in_progress'),
        value: 'in_progress',
    },
];

const page = usePage();

const isAdmin = computed(() => page.props.user?.is_administrator ?? false);

const title =
    isMovie(props.item) || isSeries(props.item)
        ? useTitle(props.item, locale.value)
        : useName(props.item, locale.value);

const isDeleting = ref<boolean[]>([]);

watch(
    props.item.reports?.data.length,
    () => {
        Array.from(
            { length: props.item.reports?.data.length ?? 0 },
            () => false,
        );
    },
    {
        immediate: true,
    },
);

function deleteReport(report, index: number) {
    isDeleting.value[index] = true;

    router.delete(
        route(`${getItemRouteName(props.item)}.reports.destroy`, {
            ...getItemRouteParams(props.item),
            report: report.id,
        }),
        {
            onFinish: () => {
                isDeleting.value[index] = false;
            },
        },
    );
}

const isUpdating = ref<boolean[]>([]);

watch(
    props.item.reports?.data.length,
    () => {
        Array.from(
            { length: props.item.reports?.data.length ?? 0 },
            () => false,
        );
    },
    {
        immediate: true,
    },
);

const updateFormArray = ref<
    ReturnType<
        typeof useForm<{
            status: string;
        }>
    >[]
>([]);

watch(
    props.item.reports?.data.length,
    () => {
        Array.from(
            { length: props.item.reports?.data.length ?? 0 },
            (_, index) => {
                updateFormArray.value.push(
                    useForm({
                        status: props.item.reports?.data[index].status,
                    }),
                );
            },
        );
    },
    {
        immediate: true,
    },
);

function updateStatus(report, status: string, index: number) {
    isUpdating.value[index] = true;

    updateFormArray.value[index].status = status;

    updateFormArray.value[index].patch(
        route(`${getItemRouteName(props.item)}.reports.update`, {
            ...getItemRouteParams(props.item),
            report: report.id,
        }),
        {
            onFinish: () => {
                isUpdating.value[index] = false;

                updateFormArray.value[index].reset();
            },
        },
    );
}

const currentPage = ref(props.item.reports?.current_page);
const loading = ref(false);
</script>

<template>
    <Head :title="`Reports for ${title}`" />

    <item-info-page :item="props.item">
        <template #header>
            <item-info-header :item="item" />
        </template>

        <template #default>
            <v-container>
                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.item.reports"
                            :loading="loading"
                        />
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-table hover>
                            <thead>
                                <tr>
                                    <th scope="col">
                                        {{ $t('reports.table.user') }}
                                    </th>

                                    <th scope="col">
                                        {{ $t('reports.table.subject') }}
                                    </th>

                                    <th scope="col">
                                        {{ $t('reports.table.status') }}
                                    </th>

                                    <th scope="col">
                                        {{ $t('reports.table.date') }}
                                    </th>

                                    <th
                                        v-if="isAdmin"
                                        scope="col"
                                    >
                                        {{ $t('reports.table.actions') }}
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr
                                    v-for="(report, index) in item.reports.data"
                                    :key="report.id"
                                >
                                    <td class="py-2 align-top">
                                        <Link
                                            :href="
                                                route('profile.show', {
                                                    user: report.reporter.id,
                                                })
                                            "
                                        >
                                            <div
                                                class="flex flex-row items-center gap-4"
                                            >
                                                <user-avatar
                                                    :user="report.reporter"
                                                />

                                                <user-name
                                                    class="font-bold"
                                                    :user="report.reporter"
                                                />
                                            </div>
                                        </Link>
                                    </td>

                                    <td class="py-2 align-top">
                                        <div
                                            class="flex flex-col align-baseline"
                                        >
                                            <p>
                                                {{
                                                    $t(
                                                        'dialogs.reportContent.subject',
                                                        { title: title },
                                                    )
                                                }}
                                            </p>

                                            <p>
                                                <span class="font-bold">
                                                    {{
                                                        $t(
                                                            'dialogs.reportContent.type',
                                                        )
                                                    }}
                                                </span>
                                                {{
                                                    $t(
                                                        `dialogs.reportContent.types.${report.type}`,
                                                    )
                                                }}
                                            </p>

                                            <p v-if="isAdmin">
                                                <span class="font-bold">
                                                    {{
                                                        $t(
                                                            'dialogs.reportContent.visibility',
                                                        )
                                                    }}
                                                </span>
                                                {{
                                                    $t(
                                                        `dialogs.reportContent.visibilities.${
                                                            report.public
                                                                ? 'public'
                                                                : 'private'
                                                        }`,
                                                    )
                                                }}
                                            </p>

                                            <p class="font-bold">
                                                {{
                                                    $t(
                                                        'dialogs.reportContent.information',
                                                    )
                                                }}
                                            </p>

                                            <p v-text="report.message" />
                                        </div>
                                    </td>

                                    <td class="py-2 align-top">
                                        <v-select
                                            v-if="isAdmin"
                                            :model-value="
                                                updateFormArray[index].status
                                            "
                                            hide-details
                                            :items="reportStatus"
                                            item-title="name"
                                            item-value="value"
                                            :loading="isUpdating[index]"
                                            @update:model-value="
                                                updateStatus(
                                                    report,
                                                    $event,
                                                    index,
                                                )
                                            "
                                        />

                                        <template v-else>
                                            {{
                                                $t(
                                                    `dialogs.reportContent.${report.status}`,
                                                )
                                            }}
                                        </template>
                                    </td>

                                    <td class="py-2 align-top">
                                        {{
                                            DateTime.fromISO(
                                                report.created_at,
                                            ).toLocaleString(
                                                DateTime.DATETIME_MED,
                                            )
                                        }}
                                    </td>

                                    <td
                                        v-if="isAdmin"
                                        class="py-2 align-baseline"
                                    >
                                        <v-btn
                                            variant="tonal"
                                            color="error"
                                            :loading="isDeleting[index]"
                                            :text="$t('general.delete')"
                                            @click="deleteReport(report, index)"
                                        />
                                    </td>
                                </tr>

                                <tr v-if="item.reports?.data.length === 0">
                                    <td colspan="4">
                                        {{
                                            $t(
                                                'dialogs.reportContent.noReports',
                                            )
                                        }}
                                    </td>
                                </tr>
                            </tbody>
                        </v-table>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <pagination
                            v-model="currentPage"
                            :paginated-data="props.item.reports"
                            :loading="loading"
                        />
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </item-info-page>
</template>
