Cast
<script setup lang="ts">
import type { Movie, Person } from '@/types/models';
import type { PropType } from 'vue';

import { router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';
import { useDisplay } from 'vuetify';

import MdiLock from '~icons/mdi/lock';
import MdiLockOpen from '~icons/mdi/lock-open';
import MdiPlus from '~icons/mdi/plus';

import DialogCreateMovieCast from '@/Components/DialogCreateMovieCast.vue';
import { EditSubroute } from '@/types/enums';
import { getName } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const page = usePage();

const isAdmin = page?.props?.user?.is_administrator;

const locale = useI18n().locale;

const deletingCastMember = ref<boolean[]>([]);
const lockingCastMember = ref<boolean[]>([]);

function deleteCastMember(person: Person, index: number) {
    deletingCastMember.value[index] = true;

    router.delete(
        route('movies.cast.destroy', {
            movie: props.item.slug,
            model: person.slug,
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
                deletingCastMember.value[index] = false;
            },
        },
    );
}

function toggleCastMemberLock(person: Person, index: number) {
    lockingCastMember.value[index] = true;

    router.patch(
        route('movies.cast.update', {
            movie: props.item.slug,
            model: person.slug,
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
                lockingCastMember.value[index] = false;
            },
        },
    );
}

const { mdAndUp, thresholds } = useDisplay();
const isAddCastMemberDialogOpen = ref(false);

watch(
    () => props.item.models?.length,
    () => {
        deletingCastMember.value = Array.from(
            new Array<boolean>(props.item.models?.length ?? 0),
            () => false,
        );

        lockingCastMember.value = Array.from(
            new Array<boolean>(props.item.models?.length ?? 0),
            () => false,
        );
    },
    {
        immediate: true,
    },
);
</script>

<template>
    <v-window-item :value="EditSubroute.Cast">
        <v-row class="mt-0">
            <v-col class="pt-0">
                <v-dialog
                    v-model="isAddCastMemberDialogOpen"
                    :fullscreen="!mdAndUp"
                    :max-width="mdAndUp ? thresholds.sm : undefined"
                >
                    <template #activator="{ props: dialogProps }">
                        <v-btn
                            v-bind="dialogProps"
                            variant="tonal"
                            :prepend-icon="MdiPlus"
                        >
                            {{ $t('edit.cast.add') }}
                        </v-btn>
                    </template>

                    <dialog-create-movie-cast
                        v-model="isAddCastMemberDialogOpen"
                        :item="props.item"
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
                                {{ $t('edit.cast.person') }}
                            </th>

                            <th scope="col">
                                <mdi-lock :aria-label="$t('edit.cast.lock')" />
                            </th>

                            <th scope="col" />
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(model, index) in props.item.models"
                            :key="`model-${model.id}`"
                        >
                            <td class="w-full">
                                <div class="flex flex-row items-center gap-4">
                                    <v-avatar color="primary">
                                        <v-img
                                            v-if="model.poster"
                                            :src="model.poster.original_url"
                                            cover
                                        />

                                        <span
                                            v-else
                                            class="font-black"
                                        >
                                            {{
                                                getName(
                                                    model,
                                                    locale,
                                                )?.[0].toUpperCase()
                                            }}
                                        </span>
                                    </v-avatar>
                                    {{ getName(model, locale) }}
                                </div>
                            </td>

                            <td>
                                <v-btn
                                    v-if="isAdmin"
                                    variant="text"
                                    :loading="lockingCastMember[index]"
                                    :icon="
                                        model.pivot.locked
                                            ? MdiLock
                                            : MdiLockOpen
                                    "
                                    @click="
                                        () => toggleCastMemberLock(model, index)
                                    "
                                />

                                <template v-else>
                                    <mdi-lock v-if="model.pivot.locked" />

                                    <mdi-lock-open v-else />
                                </template>
                            </td>

                            <td>
                                <v-btn
                                    v-if="isAdmin"
                                    variant="tonal"
                                    color="error"
                                    :loading="deletingCastMember[index]"
                                    @click="
                                        () => deleteCastMember(model, index)
                                    "
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
