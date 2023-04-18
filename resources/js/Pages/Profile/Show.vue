<script setup lang="ts">
import { Head, usePage } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { PropType } from 'vue';

import AppLayout from '@/Layouts/AppLayout.vue';
import { PageProps } from '@/types/inertia';
import { User } from '@/types/models';

defineOptions({
    layout: AppLayout,
});

const props = defineProps({
    user: {
        type: Object as PropType<User>,
        required: true,
    },
    isCurrentUser: {
        type: Boolean,
        required: true,
    },
    editsCount: {
        type: Number,
        required: true,
    },
    favoritesCount: {
        type: Number,
        required: true,
    },
    collectionCount: {
        type: Number,
        required: true,
    },
    wishlistCount: {
        type: Number,
        required: true,
    },
});

const page = usePage<PageProps>();
</script>

<template>
    <Head
        :title="`${page?.props?.user?.name} - ${$t(
            'web.settings.account.title',
        )}`"
    />

    <div class="col bg-grey-3">
        <div class="row q-py-lg q-px-md items-center">
            <q-avatar
                class="q-mr-md"
                color="secondary"
                size="100px"
                :src="
                    props.user.profile_photo_path
                        ? props.user.profile_photo_path
                        : null
                "
            >
                <template v-if="!props.user.profile_photo_path">
                    <span class="text-h2 text-weight-bolder text-white">
                        {{ props.user.name.charAt(0) }}
                    </span>
                </template>
            </q-avatar>
            <div class="row items-end">
                <h1
                    class="text-h3 text-grey-8 text-weight-bold q-mt-none q-mb-none ellipsis-2-lines"
                >
                    {{ page?.props?.user?.name }}
                </h1>
                <span
                    v-if="page?.props?.user"
                    class="text-h6 q-ml-md text-grey-7"
                >
                    {{
                        $t('web.profile.member_since', {
                            date: DateTime.fromISO(
                                page?.props?.user?.created_at,
                            ).toLocaleString(DateTime.DATE_FULL),
                        })
                    }}
                </span>
            </div>
        </div>
        <div class="row q-pb-sm q-px-md">
            <h2 class="text-h4 q-my-none">{{ $t('web.profile.stats') }}</h2>
        </div>
        <div class="row q-pt-none q-pb-lg q-px-md">
            <div class="fit grid-4">
                <div class="column">
                    <span class="text-h6 q-mt-none q-mb-none text-grey-7">
                        {{ $t('web.profile.edits') }}
                    </span>
                    <span
                        class="text-h3 text-weight-bolder q-mt-none q-mb-none text-secondary"
                    >
                        {{ props.editsCount.toLocaleString() }}
                    </span>
                </div>
                <div class="column">
                    <span class="text-h6 q-mt-none q-mb-none text-grey-7">
                        {{ $t('web.profile.favorites') }}
                    </span>
                    <span
                        class="text-h3 text-weight-bolder q-mt-none q-mb-none text-secondary"
                    >
                        {{ props.favoritesCount.toLocaleString() }}
                    </span>
                </div>
                <div class="column">
                    <span class="text-h6 q-mt-none q-mb-none text-grey-7">
                        {{ $t('web.profile.collection') }}
                    </span>
                    <span
                        class="text-h3 text-weight-bolder q-mt-none q-mb-none text-secondary"
                    >
                        {{ props.collectionCount.toLocaleString() }}
                    </span>
                </div>
                <div class="column">
                    <span class="text-h6 q-mt-none q-mb-none text-grey-7">
                        {{ $t('web.profile.wishlist') }}
                    </span>
                    <span
                        class="text-h3 text-weight-bolder q-mt-none q-mb-none text-secondary"
                    >
                        {{ props.wishlistCount.toLocaleString() }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>
