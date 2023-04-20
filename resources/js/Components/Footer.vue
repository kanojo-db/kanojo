<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <footer class="footer p-10 bg-neutral text-white">
        <div>
            <img
                alt="Kanojo Logo"
                src="/images/logo-dark.svg"
                style="width: 150px; height: 48px"
                fit="contain"
            />
        </div>
        <div>
            <span class="footer-title">
                {{ $t('web.general.pages.general') }}
            </span>
            <Link
                :href="route('about.index')"
                class="link link-hover"
            >
                {{ $t('web.general.pages.about') }}
            </Link>
            <Link
                :href="route('privacy')"
                class="link link-hover"
            >
                {{ $t('web.general.pages.privacyPolicy') }}
            </Link>
            <Link
                href="#"
                class="link link-hover"
            >
                {{ $t('web.general.pages.contact_us') }}
            </Link>
            <a
                class="link link-hover"
                :href="route('scribe')"
            >
                {{ $t('web.general.pages.api') }}
            </a>
            <a
                class="link link-hover"
                href="https://kanojo1.statuspage.io/"
            >
                {{ $t('web.general.pages.status') }}
            </a>
        </div>
        <div>
            <span class="footer-title">
                {{ $t('web.general.pages.contribute') }}
            </span>
            <Link
                class="link link-hover"
                :href="route('bible.index')"
            >
                {{ $t('web.general.pages.bible') }}
            </Link>
            <Link
                class="link link-hover"
                :href="route('movies.create')"
            >
                {{ $t('web.general.pages.add_movie') }}
            </Link>
            <Link
                class="link link-hover"
                :href="route('movies.create')"
            >
                {{ $t('web.general.pages.fill_missing') }}
            </Link>
            <Link
                class="link link-hover"
                href="https://hosted.weblate.org/projects/kanojo/website/"
            >
                {{ $t('web.general.pages.help_translate') }}
            </Link>
        </div>
        <div>
            <span class="footer-title">
                {{ $t('web.general.pages.community') }}
            </span>
            <a
                class="link link-hover"
                href="https://kanojo.sleekplan.app"
            >
                {{ $t('web.general.pages.requestFeatures') }}
            </a>
            <a
                class="link link-hover"
                href="https://discord.gg/chg5KzTHHp"
            >
                {{ $t('web.general.pages.discord') }}
            </a>
            <a
                class="link link-hover"
                href="https://github.com/kanojo-db"
            >
                {{ $t('web.general.pages.github') }}
            </a>
            <a
                class="link link-hover"
                href="https://twitter.com/kanojodb"
            >
                {{ $t('web.general.pages.twitter') }}
            </a>
        </div>
    </footer>
</template>
