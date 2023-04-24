<script setup lang="ts">
import type { Series } from '@/types/models';
import type { PropType } from 'vue';

import { useI18n } from 'vue-i18n';

import { useTitle } from '@/utils/item';

const props = defineProps({
    item: {
        type: Object as PropType<Series>,
        required: true,
    },
});

const { locale } = useI18n();

const title = useTitle(props.item, locale.value);
</script>

<template>
    <div class="flex w-full flex-col">
        <div class="flex flex-row items-center gap-2">
            <h1
                itemprop="name"
                class="line-clamp-2 text-ellipsis text-4xl font-extrabold"
            >
                {{ title }}
            </h1>

            <v-chip class="ml-2">
                {{
                    $t('personShow.moviesCount', {
                        number: props.item.movies_count?.toLocaleString(),
                    })
                }}
            </v-chip>
        </div>
    </div>
</template>
