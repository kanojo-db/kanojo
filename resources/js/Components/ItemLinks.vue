<script setup lang="ts">
import type { Item } from '@/types/models';
import type { PropType } from 'vue';

import { computed } from 'vue';

import Ameba from '~icons/custom/ameba';
import Fanza from '~icons/custom/fanza';
import MdiHelp from '~icons/mdi/help';
import OfficeBuilding from '~icons/mdi/office-building';
import Google from '~icons/simple-icons/google';
import IMDb from '~icons/simple-icons/imdb';
import Instagram from '~icons/simple-icons/instagram';
import Line from '~icons/simple-icons/line';
import OnlyFans from '~icons/simple-icons/onlyfans';
import TheMovieDb from '~icons/simple-icons/themoviedatabase';
import TikTok from '~icons/simple-icons/tiktok';
import Twitter from '~icons/simple-icons/twitter';
import Wikidata from '~icons/simple-icons/wikidata';
import YouTube from '~icons/simple-icons/youtube';

const props = defineProps({
    item: {
        type: Object as PropType<Item>,
        required: true,
    },
});

const filteredLinks = computed(() => {
    // Return an object with only the links that have a value
    return Object.fromEntries(
        Object.entries<string>(props.item.external_links).filter(
            ([, value]) => value,
        ),
    );
});

const getIconComponent = function (linkType: string) {
    switch (linkType) {
        case 'twitter':
            return Twitter;
        case 'instagram':
            return Instagram;
        case 'tiktok':
            return TikTok;
        case 'ameblo':
            return Ameba;
        case 'wikidata':
            return Wikidata;
        case 'youtube':
            return YouTube;
        case 'google':
            return Google;
        case 'imdb':
            return IMDb;
        case 'fanza':
            return Fanza;
        case 'tmdb':
            return TheMovieDb;
        case 'line_blog':
            return Line;
        case 'onlyfans':
            return OnlyFans;
        case 'corporate':
            return OfficeBuilding;
        default:
            return MdiHelp;
    }
};

const getLinkName = function (linkType: string) {
    switch (linkType) {
        case 'twitter':
            return 'Twitter';
        case 'instagram':
            return 'Instagram';
        case 'tiktok':
            return 'TikTok';
        case 'ameblo':
            return 'Ameblo';
        case 'wikidata':
            return 'Wikidata';
        case 'youtube':
            return 'YouTube';
        case 'google':
            return 'Google';
        case 'imdb':
            return 'IMDb';
        case 'fanza':
            return 'Fanza';
        case 'tmdb':
            return 'The Movie Database';
        case 'line_blog':
            return 'LINE Blog';
        case 'onlyfans':
            return 'OnlyFans';
        case 'corporate':
            return 'Japanese Tax Agency';
        default:
            return 'Unknown';
    }
};
</script>

<template>
    <div class="mb-2 flex flex-col gap-2">
        <p
            v-if="Object.keys(filteredLinks).length === 0"
            class="italic"
        >
            {{ $t('item.links.none') }}
        </p>

        <a
            v-for="(value, key) in filteredLinks"
            :key="`external-link-${key}`"
            :href="value"
            class="flex flex-row items-center gap-2 hover:underline"
            target="_blank"
        >
            <component
                :is="getIconComponent(key.toString())"
                class="h-6 w-6"
            />
            {{ getLinkName(key.toString()) }}
        </a>
    </div>
</template>
