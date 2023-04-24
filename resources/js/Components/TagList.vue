<script setup lang="ts">
import type { Tag, Tags } from '@/types/models';
import type { PropType } from 'vue';

import { computed } from 'vue';

import ContentTag from './ContentTag.vue';

const props = defineProps({
    tags: {
        type: Array as PropType<Tags>,
        required: true,
    },
});

const groupedTags = computed<Tags>(() => {
    const groupedTags = props.tags.reduce((acc, tag) => {
        if (!acc[tag.type]) {
            acc[tag.type] = [];
        }

        acc[tag.type].push(tag);

        return acc;
    }, {});

    return groupedTags;
});
</script>

<template>
    <div class="flex flex-col gap-2">
        <div
            v-for="(tagGroupItems, tagGroupName, tagGroupIndex) in groupedTags"
            :key="`movie-tag-group-${tagGroupIndex}`"
            class="flex flex-col lg:flex-row"
        >
            <div
                class="lg:text-md mb-1 w-[6em] pr-4 align-middle text-lg font-bold lg:mb-0 lg:w-32"
            >
                {{
                    tagGroupName === 'null'
                        ? $t('movie.show.unknown_birth_date')
                        : tagGroupName
                }}
            </div>

            <div class="justify-center gap-2 align-middle">
                <div class="flex flex-row flex-wrap gap-2">
                    <content-tag
                        v-for="tag in tagGroupItems"
                        :key="`movie-tag-${tag.id}`"
                        :tag="tag as unknown as Tag"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
