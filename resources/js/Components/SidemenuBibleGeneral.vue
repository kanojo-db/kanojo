<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
</script>

<template>
    <q-card
        flat
        bordered
    >
        <q-card-section class="row items-center bg-primary text-stone-50">
            <div class="text-weight-bold text-h6">
                {{ $t('bible.sections.general') }}
            </div>
        </q-card-section>

        <v-divider />

        <q-list>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        page.component === 'Bible/Generalt',
                }"
            >
                <q-item-section>
                    {{ $t('bible.general.general_tips') }}
                </q-item-section>
            </q-item>
        </q-list>
    </q-card>
</template>
