<script setup>
import { DateTime } from 'luxon';

import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: {
        type: Object,
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
</script>

<template>
    <AppLayout
        :title="`${$page.props.user.name} - ${$t(
            'web.settings.account.title',
        )}`"
    >
        <div class="col bg-grey-3">
            <div class="row q-py-lg q-px-md items-end">
                <h1
                    class="text-h3 text-grey-8 text-weight-bold q-mt-none q-mb-none ellipsis-2-lines"
                >
                    {{ $page.props.user.name }}
                </h1>
                <span class="text-h6 q-ml-md text-grey-7">
                    {{
                        $t('web.profile.member_since', {
                            date: DateTime.fromISO(
                                $page.props.user.created_at,
                            ).toLocaleString(DateTime.DATE_FULL),
                        })
                    }}
                </span>
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
    </AppLayout>
</template>
