<script setup lang="ts">
import type { Studio } from '@/types/models';
import type { PropType } from 'vue';

import { useI18n } from 'vue-i18n';

import CardSwiper from '@/Components/CardSwiper.vue';
import { useName } from '@/utils/item';

interface StudioWithMoviesCount extends Studio {
    movies_count: number;
}

const props = defineProps({
    item: {
        type: Object as PropType<StudioWithMoviesCount>,
        required: true,
    },
});

const { locale } = useI18n();

const name = useName(props.item, locale.value);
</script>

<template>
    <div class="flex w-full flex-col">
        <div class="mb-4 flex flex-col items-start">
            <div class="flex flex-row items-center gap-2">
                <meta
                    itemprop="name"
                    :content="name"
                />

                <h1 class="line-clamp-2 text-ellipsis text-4xl font-extrabold">
                    {{ name }}
                </h1>

                <v-chip>
                    {{
                        $t('personShow.moviesCount', {
                            number: item.movies_count.toLocaleString(),
                        })
                    }}
                </v-chip>
            </div>
        </div>

        <div
            v-if="props.item.models.length > 0"
            class="relative"
        >
            <h2 class="mb-2 text-xl font-bold">
                {{ $t('studio.mostFeaturedModels') }}
            </h2>

            <card-swiper :items="props.item.models" />
        </div>
    </div>
</template>
