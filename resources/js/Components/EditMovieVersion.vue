<script setup lang="ts">
import type { Movie, MovieVersion } from '@/types/models';
import type { PropType } from 'vue';

import { router, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { ref, watch } from 'vue';
import { useDisplay } from 'vuetify';

import MdiPlus from '~icons/mdi/plus';

import DialogEditMovieVersion from '@/Components/DialogEditMovieVersion.vue';
import DialogMovieVersionCreate from '@/Components/DialogMovieVersionCreate.vue';
import { EditSubroute } from '@/types/enums';

const props = defineProps({
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const page = usePage();

const isAdmin = page?.props?.user?.is_administrator;

const isDialogOpen = ref(false);
const isEditDialogOpen = ref<boolean[]>([]);

const isDeleting = ref<boolean[]>([]);

const { mdAndUp, thresholds } = useDisplay();

function deleteVersion(version: MovieVersion, index: number) {
    isDeleting.value[index] = true;

    router.delete(
        route('version.destroy', {
            version: version.id,
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
                isDeleting.value[index] = false;
            },
        },
    );
}

watch(
    () => props.item.versions?.length,
    () => {
        isEditDialogOpen.value = Array.from(
            new Array<boolean>(props.item.models?.length ?? 0),
            () => false,
        );

        isDeleting.value = Array.from(
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
    <v-window-item :value="EditSubroute.Versions">
        <v-row class="mt-0">
            <v-col class="pt-0">
                <v-dialog
                    v-model="isDialogOpen"
                    :fullscreen="!mdAndUp"
                    :max-width="mdAndUp ? thresholds.sm : undefined"
                >
                    <template #activator="{ props: dialogProps }">
                        <v-btn
                            v-bind="dialogProps"
                            variant="tonal"
                            :prepend-icon="MdiPlus"
                        >
                            {{ $t('edit.version.add') }}
                        </v-btn>
                    </template>

                    <dialog-movie-version-create
                        v-model="isDialogOpen"
                        :item="props.item"
                    />
                </v-dialog>
            </v-col>
        </v-row>

        <v-row class="mt-0">
            <v-col>
                <v-table>
                    <thead>
                        <tr>
                            <th scope="col">
                                {{ $t('movie.show.format') }}
                            </th>

                            <th scope="col">
                                {{ $t('movie.show.product_code') }}
                            </th>

                            <th scope="col">
                                {{ $t('movie.show.barcode') }}
                            </th>

                            <th scope="col">
                                {{ $t('movie.show.release_date') }}
                            </th>

                            <th scope="col" />
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="(version, index) in props.item.versions"
                            :key="`version-${version.id}`"
                        >
                            <td>
                                {{ version.format }}
                            </td>

                            <td>
                                {{ version.product_code }}
                            </td>

                            <td>
                                {{ version.barcode }}
                            </td>

                            <td>
                                {{
                                    version.release_date
                                        ? DateTime.fromISO(
                                              version.release_date,
                                          ).toLocaleString(DateTime.DATE_MED)
                                        : $t('general.unknown')
                                }}
                            </td>

                            <td>
                                <div class="flex flex-row gap-2">
                                    <v-dialog
                                        v-model="isEditDialogOpen[index]"
                                        :fullscreen="!mdAndUp"
                                        :max-width="
                                            mdAndUp ? thresholds.sm : undefined
                                        "
                                    >
                                        <template
                                            #activator="{ props: dialogProps }"
                                        >
                                            <v-btn
                                                v-bind="dialogProps"
                                                variant="tonal"
                                            >
                                                {{ $t('general.edit') }}
                                            </v-btn>
                                        </template>

                                        <dialog-edit-movie-version
                                            v-model="isEditDialogOpen[index]"
                                            :item="props.item"
                                            :version="version"
                                        />
                                    </v-dialog>

                                    <v-btn
                                        v-if="isAdmin"
                                        variant="tonal"
                                        color="error"
                                        :loading="isDeleting[index]"
                                        @click="
                                            () => deleteVersion(version, index)
                                        "
                                    >
                                        {{ $t('general.delete') }}
                                    </v-btn>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
            </v-col>
        </v-row>
    </v-window-item>
</template>
