<script setup>
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
            <div class="row q-py-lg q-px-md">
                <h1 class="text-h4 q-mt-none q-mb-none ellipsis-2-lines">
                    {{ $page.props.user.name }}
                </h1>
            </div>
            <div class="row q-pb-lg q-px-md">
                <div class="fit grid-4">
                    <div class="column">
                        <span class="text-h6 q-mt-none q-mb-none"> Edits </span>
                        <span class="text-h4 q-mt-none q-mb-none">
                            {{ props.editsCount ?? 0 }}
                        </span>
                    </div>
                    <div class="column">
                        <span class="text-h6 q-mt-none q-mb-none">
                            Favorites
                        </span>
                        <span class="text-h4 q-mt-none q-mb-none">
                            {{ props.favoritesCount ?? 0 }}
                        </span>
                    </div>
                    <div class="column">
                        <span class="text-h6 q-mt-none q-mb-none">
                            Collection
                        </span>
                        <span class="text-h4 q-mt-none q-mb-none">
                            {{ props.collectionCount ?? 0 }}
                        </span>
                    </div>
                    <div class="column">
                        <span class="text-h6 q-mt-none q-mb-none">
                            Wishlist
                        </span>
                        <span class="text-h4 q-mt-none q-mb-none">
                            {{ props.wishlistCount ?? 0 }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
