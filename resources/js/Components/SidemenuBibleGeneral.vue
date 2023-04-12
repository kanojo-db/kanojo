<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

import { PageProps } from '@/types/inertia';

const page = usePage<PageProps>();
</script>

<template>
    <q-card
        flat
        bordered
    >
        <q-card-section class="bg-primary text-white row items-center">
            <div class="text-weight-bold text-h6">
                {{ $t('web.bible.sections.general') }}
            </div>
        </q-card-section>

        <q-separator />

        <q-list>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        page?.component === 'Bible/Generalt',
                }"
            >
                <q-item-section>
                    {{ $t('web.bible.general.general_tips') }}
                </q-item-section>
            </q-item>
        </q-list>
    </q-card>
</template>
