<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <v-footer
        class="grow-0 flex-col items-start justify-center gap-4 bg-stone-600 p-10 text-stone-50 dark:bg-stone-800 md:flex-row md:gap-16"
    >
        <div>
            <img
                alt="Kanojo Logo"
                src="/images/logo-dark.svg"
                class="h-[48px]] w-[150px] object-contain"
            />
        </div>

        <div class="flex flex-col gap-4 md:flex-row md:gap-36">
            <div class="flex flex-col">
                <span class="mb-2 font-bold">
                    {{ $t('general.pages.general') }}
                </span>

                <Link
                    :href="route('about.index')"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.about') }}
                </Link>

                <Link
                    :href="route('plugins')"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.plugins') }}
                </Link>

                <Link
                    :href="route('privacy')"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.privacyPolicy') }}
                </Link>

                <a
                    :href="route('scribe')"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.api') }}
                </a>
            </div>

            <div class="flex flex-col">
                <span class="mb-2 font-bold">
                    {{ $t('general.pages.contribute') }}
                </span>

                <Link
                    href="https://hosted.weblate.org/projects/kanojo/website/"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.helpTranslate') }}
                </Link>
            </div>

            <div class="flex flex-col">
                <span class="mb-2 font-bold">
                    {{ $t('general.pages.community') }}
                </span>

                <a
                    href="https://kanojo.sleekplan.app"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.requestFeatures') }}
                </a>

                <a
                    href="https://discord.gg/chg5KzTHHp"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.discord') }}
                </a>

                <a
                    href="https://github.com/kanojo-db"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.github') }}
                </a>

                <a
                    rel="me"
                    href="https://mastodon.social/@kanojodb"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.mastodon') }}
                </a>

                <a
                    href="https://twitter.com/kanojodb"
                    class="hover:text-secondary"
                >
                    {{ $t('general.pages.twitter') }}
                </a>
            </div>
        </div>
    </v-footer>
</template>
