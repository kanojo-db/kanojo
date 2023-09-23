<script setup lang="ts">
import type { Users } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

import UserName from '@/Components/UserName.vue';

import UserAvatar from './UserAvatar.vue';

const props = defineProps({
    topUsers: {
        type: Object as PropType<Users>,
        required: true,
    },
});

const maxTotalAudits = computed(() => {
    let max = 0;

    props.topUsers.forEach((user) => {
        if (user.total_audits > max) {
            max = user.total_audits;
        }
    });

    return max;
});

const maxAuditsThisWeek = computed(() => {
    let max = 0;

    props.topUsers.forEach((user) => {
        if (user.audits_this_week > max) {
            max = user.audits_this_week;
        }
    });

    return max;
});

const orderedTopUsers = computed(() => {
    const topUsers = props.topUsers;

    // Sort by audits this week, descending, then by total audits, descending

    return topUsers.sort((a, b) => {
        if (a.audits_this_week > b.audits_this_week) {
            return -1;
        }

        if (a.audits_this_week < b.audits_this_week) {
            return 1;
        }

        if (a.total_audits > b.total_audits) {
            return -1;
        }

        if (a.total_audits < b.total_audits) {
            return 1;
        }

        return 0;
    });
});
</script>

<template>
    <section>
        <div class="mb-6 mt-10 flex flex-row">
            <h1 class="text-2xl font-bold">
                {{ $t('welcome.leaderboard') }}
            </h1>

            <div class="ml-4 flex flex-col items-start justify-center">
                <div class="flex items-center">
                    <div class="h-2 w-2 rounded-full bg-primary" />

                    <div class="ml-2 text-sm font-bold">
                        {{ $t('welcome.leaderboardTotalEdits') }}
                    </div>
                </div>

                <div class="flex items-center">
                    <div class="h-2 w-2 rounded-full bg-secondary" />

                    <div class="ml-2 text-sm font-bold">
                        {{ $t('welcome.leaderboardEditsThisWeek') }}
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-8 grid grid-cols-1 gap-x-8 gap-y-4 md:grid-cols-2">
            <div
                v-for="user in orderedTopUsers"
                :key="`top-user-${user.id}`"
                class="flex w-full flex-row items-center"
            >
                <Link :href="route('users.show', user)">
                    <user-avatar
                        :user="user"
                        size="x-large"
                    />
                </Link>

                <div class="ml-4 flex grow flex-col">
                    <Link :href="route('users.show', user)">
                        <user-name
                            class="mb-1 text-2xl font-bold"
                            :user="user"
                        />
                    </Link>

                    <div class="flex flex-row flex-nowrap items-center">
                        <div
                            class="h-[10px] rounded bg-primary"
                            :style="`width: ${
                                maxTotalAudits === 0
                                    ? 0
                                    : (user.total_audits / maxTotalAudits) * 100
                            }%;`"
                        />

                        <div class="ml-2 font-bold">
                            {{ user.total_audits.toLocaleString() }}
                        </div>
                    </div>

                    <div class="flex flex-row flex-nowrap items-center">
                        <div
                            class="h-[10px] rounded bg-secondary"
                            :style="`width: ${
                                maxAuditsThisWeek === 0
                                    ? 0
                                    : (user.audits_this_week /
                                          maxAuditsThisWeek) *
                                      100
                            }%; height: 10px`"
                        />

                        <div class="ml-2 font-bold">
                            {{ user.audits_this_week.toLocaleString() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
