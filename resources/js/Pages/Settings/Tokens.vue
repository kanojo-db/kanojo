<script setup lang="ts">
import type { Token, Tokens, User } from '@/types/models';
import type { PropType } from 'vue';

import { Head, router, usePage } from '@inertiajs/vue3';
import copy from 'copy-to-clipboard';
import { DateTime } from 'luxon';
import { ref, watch } from 'vue';
import { useDisplay } from 'vuetify';

import MdiContentCopy from '~icons/mdi/content-copy';
import MdiPlus from '~icons/mdi/plus';

import DialogCreateToken from '@/Components/DialogCreateToken.vue';
import SideMenuPage from '@/Components/SideMenuPage.vue';
import SideMenuUser from '@/Components/SideMenuUser.vue';
import UserAvatar from '@/Components/UserAvatar.vue';
import UserName from '@/Components/UserName.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    tokens: {
        type: Array as PropType<Tokens>,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const page = usePage();

const copyToken = (token: string) => {
    copy(token, {
        format: 'text/plain',
    });
};

const deleteToken = (token: Token, index: number) => {
    isTokenDeleting.value[index] = true;

    router.delete(
        route('settings.tokens.destroy', {
            token: token.id,
        }),
        {
            preserveScroll: true,
            onFinish: () => {
                isTokenDeleting.value[index] = false;
            },
        },
    );
};

const isTokenDeleting = ref<boolean[]>([]);

const { mdAndUp, thresholds } = useDisplay();
const isCreateDialogOpen = ref(false);

watch(
    () => props.tokens.length,
    () => {
        isTokenDeleting.value = Array.from(
            { length: props.tokens.length },
            () => false,
        );
    },
    {
        immediate: true,
    },
);
</script>

<template>
    <Head
        :title="`${page?.props?.user?.name} - ${$t('settings.tokens.title')}`"
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
            <v-banner v-if="props.tokens.length === 0">
                {{ $t('settings.tokens.no_tokens') }}
                <template #actions>
                    <v-dialog
                        v-model="isCreateDialogOpen"
                        :fullscreen="!mdAndUp"
                        :max-width="mdAndUp ? thresholds.sm : undefined"
                    >
                        <template #activator="{ props: dialogProps }">
                            <v-btn
                                v-bind="dialogProps"
                                variant="tonal"
                                :prepend-icon="MdiPlus"
                                :text="$t('settings.tokens.create_token')"
                            />
                        </template>

                        <dialog-create-token v-model="isCreateDialogOpen" />
                    </v-dialog>
                </template>
            </v-banner>

            <template v-else>
                <v-row class="mt-0">
                    <v-col class="pt-0">
                        <v-dialog
                            v-model="isCreateDialogOpen"
                            :fullscreen="!mdAndUp"
                            :max-width="mdAndUp ? thresholds.sm : undefined"
                        >
                            <template #activator="{ props: dialogProps }">
                                <v-btn
                                    v-bind="dialogProps"
                                    variant="tonal"
                                    :prepend-icon="MdiPlus"
                                    :text="$t('settings.tokens.create_token')"
                                />
                            </template>

                            <dialog-create-token v-model="isCreateDialogOpen" />
                        </v-dialog>
                    </v-col>
                </v-row>

                <v-banner v-if="$attrs._session?._flash?.token">
                    {{ $t('settings.tokens.success') }}
                    <template #actions>
                        <code class="q-py-sm q-px-md bg-grey-2">
                            {{ $attrs._session?._flash?.token }}
                            <v-btn
                                :icon="MdiContentCopy"
                                @click="
                                    () =>
                                        copyToken(
                                            $attrs._session?._flash?.token,
                                        )
                                "
                            />
                        </code>
                    </template>
                </v-banner>

                <v-table hover>
                    <thead>
                        <tr>
                            <th scope="col">
                                {{ $t('settings.tokens.name') }}
                            </th>

                            <th scope="col">
                                {{ $t('settings.tokens.last_used') }}
                            </th>

                            <th scope="col">
                                {{ $t('settings.tokens.created') }}
                            </th>

                            <th scope="col">
                                {{ $t('settings.tokens.actions') }}
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <template v-if="props.tokens.length > 0">
                            <tr
                                v-for="(token, index) in props.tokens"
                                :key="token.id"
                            >
                                <td>
                                    {{ token.name }}
                                </td>

                                <td>
                                    {{
                                        token.last_used_at
                                            ? DateTime.fromISO(
                                                  token.last_used_at,
                                              ).toLocaleString(
                                                  DateTime.DATETIME_SHORT,
                                              )
                                            : $t('settings.tokens.neverUsed')
                                    }}
                                </td>

                                <td>
                                    {{
                                        DateTime.fromISO(
                                            token.created_at,
                                        ).toLocaleString(
                                            DateTime.DATETIME_SHORT,
                                        )
                                    }}
                                </td>

                                <td>
                                    <v-btn
                                        text="Delete"
                                        variant="flat"
                                        color="error"
                                        :loading="isTokenDeleting[index]"
                                        @click="() => deleteToken(token, index)"
                                    />
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </v-table>
            </template>
        </template>
    </side-menu-page>
</template>
