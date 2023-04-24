Cast
<script setup lang="ts">
import type { Person, PersonAlias } from '@/types/models';
import type { PropType } from 'vue';

import { router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useDisplay } from 'vuetify';

import MdiLock from '~icons/mdi/lock';
import MdiLockOpen from '~icons/mdi/lock-open';
import MdiPlus from '~icons/mdi/plus';

import DialogCreateAlias from '@/Components/DialogCreateAlias.vue';
import { EditSubroute } from '@/types/enums';

const props = defineProps({
    item: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const page = usePage();

const isAdmin = page?.props?.user?.is_administrator;

const deletingAlias = ref<boolean[]>([]);
const lockingAlias = ref<boolean[]>([]);

function deleteAlias(alias: PersonAlias, index: number) {
    deletingAlias.value[index] = true;

    router.delete(
        route('models.alternative-names.destroy', {
            model: props.item.slug,
            alias: alias.id,
        }),
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
            onFinish: () => {
                deletingAlias.value[index] = false;
            },
        },
    );
}

const { mdAndUp, thresholds } = useDisplay();
const isAddAliasDialogOpen = ref(false);

watch(
    () => props.item.aliases?.length,
    () => {
        deletingAlias.value = Array.from(
            new Array<boolean>(props.item.aliases?.length ?? 0),
            () => false,
        );

        lockingAlias.value = Array.from(
            new Array<boolean>(props.item.aliases?.length ?? 0),
            () => false,
        );
    },
    {
        immediate: true,
    },
);

function toggleAliasLock(alias: PersonAlias, index: number) {
    lockingAlias.value[index] = true;

    router.patch(
        route('movies.cast.update', {
            model: props.item.slug,
            alias: alias.id,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
            onFinish: () => {
                lockingAlias.value[index] = false;
            },
        },
    );
}
</script>

<template>
    <v-window-item :value="EditSubroute.AlternativeNames">
        <v-row class="mt-0">
            <v-col class="pt-0">
                <v-dialog
                    v-model="isAddAliasDialogOpen"
                    :fullscreen="!mdAndUp"
                    :max-width="mdAndUp ? thresholds.sm : undefined"
                >
                    <template #activator="{ props: dialogProps }">
                        <v-btn
                            v-bind="dialogProps"
                            variant="tonal"
                            :prepend-icon="MdiPlus"
                        >
                            {{ $t('edit.aliases.add') }}
                        </v-btn>
                    </template>

                    <dialog-create-alias
                        :model-value="isAddAliasDialogOpen"
                        :person="props.item"
                        @update:model-value="isAddAliasDialogOpen = $event"
                    />
                </v-dialog>
            </v-col>
        </v-row>

        <v-row class="mt-0">
            <v-col>
                <v-table hover>
                    <thead>
                        <tr>
                            <th scope="col">
                                {{ $t('edit.alias.name') }}
                            </th>

                            <th
                                class="w-12"
                                scope="col"
                            >
                                <mdi-lock :aria-label="$t('edit.cast.lock')" />
                            </th>

                            <th
                                class="w-32"
                                scope="col"
                            />
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(alias, index) in props.item.aliases"
                            :key="`alias-${alias.id}`"
                        >
                            <td class="w-full">
                                {{ alias.alias }}
                            </td>

                            <td>
                                <v-btn
                                    v-if="isAdmin"
                                    variant="text"
                                    :loading="lockingAlias[index]"
                                    :icon="alias.locked ? MdiLock : MdiLockOpen"
                                    @click="() => toggleAliasLock(alias, index)"
                                />

                                <template v-else>
                                    <mdi-lock v-if="alias.locked" />

                                    <mdi-lock-open v-else />
                                </template>
                            </td>

                            <td>
                                <v-btn
                                    v-if="isAdmin"
                                    variant="tonal"
                                    color="error"
                                    :loading="deletingAlias[index]"
                                    @click="() => deleteAlias(alias, index)"
                                >
                                    {{ $t('general.delete') }}
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>
    </v-window-item>
</template>
