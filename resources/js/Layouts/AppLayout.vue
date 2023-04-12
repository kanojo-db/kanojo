<script setup lang="ts">
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { mdiMagnify, mdiPlus } from '@quasar/extras/mdi-v6';
import { ref } from 'vue';

import LanguageSwitcher from '@/Components/LanguageSwitcher.vue';
import { PageProps } from '@/types/inertia';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
    itemscope: {
        type: Boolean,
        required: false,
        default: null,
    },
    itemtype: {
        type: String,
        required: false,
        default: '',
    },
});

const page = usePage<PageProps>();

const showSearch = ref(false);

const logout = () => {
    router.post(route('logout'));
};

const currentRouteParams = ref(route().params);

const searchForm = useForm({
    q: currentRouteParams.value.q || '',
});

const submit = () => {
    searchForm
        .transform(({ q }) => {
            if (route().params.type) {
                return { q, type: route().params.type };
            }

            return { q: q.trim() };
        })
        .get(route('search'));
};

const websiteSchema = ref({
    '@context': 'https://schema.org/',
    '@type': 'WebSite',
    name: 'Kanojo',
    url: 'http://localhost',
    potentialAction: {
        '@type': 'SearchAction',
        target: 'http://localhost/search?q={search_term_string}',
        'query-input': 'required name=search_term_string',
    },
});
</script>

<template>
    <Head :title="props.title">
        <component
            :is="'script'"
            type="application/ld+json"
        >
            {{ websiteSchema }}
        </component>
    </Head>

    <q-layout>
        <q-header height-hint="64">
            <q-toolbar class="bg-grey-8 q-py-sm q-px-md">
                <Link href="/">
                    <img
                        alt="Kanojo Logo"
                        src="/images/logo-dark.svg"
                        style="width: 150px; height: 48px"
                        fit="contain"
                    />
                </Link>

                <q-space />

                <q-btn
                    round
                    flat
                    dense
                    :icon="mdiPlus"
                    class="q-mr-lg"
                    @click="$inertia.get(route('movies.create'))"
                />
                <LanguageSwitcher />
                <q-avatar
                    v-if="page.props.user"
                    size="32px"
                    color="white"
                >
                    <q-icon
                        class="cursor-pointer"
                        name="mdi-account"
                        size="24px"
                        color="pink-3"
                    />
                    <q-menu
                        dense
                        auto-close
                    >
                        <q-list
                            dense
                            style="width: 200px; max-width: 200px"
                        >
                            <q-item
                                clickable
                                @click="
                                    $inertia.get(
                                        route('profile.show', {
                                            user: page.props.user?.id ?? 0,
                                        }),
                                    )
                                "
                            >
                                <q-item-section class="q-py-md">
                                    <q-item-label class="text-weight-bold">
                                        {{ page.props.user.name }}
                                    </q-item-label>
                                    <q-item-label caption>
                                        {{ $t('web.general.view_profile') }}
                                    </q-item-label>
                                </q-item-section>
                            </q-item>
                            <q-separator class="q-my-xs" />
                            <q-item
                                clickable
                                @click="$inertia.get(route('settings.account'))"
                            >
                                <q-item-section>
                                    {{ $t('web.general.pages.settings') }}
                                </q-item-section>
                            </q-item>
                            <q-separator class="q-my-xs" />
                            <q-item
                                clickable
                                @click="logout"
                            >
                                <q-item-section>
                                    {{ $t('web.general.logout') }}
                                </q-item-section>
                            </q-item>
                        </q-list>
                    </q-menu>
                </q-avatar>
                <template v-else>
                    <q-btn
                        flat
                        @click="$inertia.get(route('login'))"
                    >
                        {{ $t('web.general.login') }}
                    </q-btn>
                    <q-btn
                        flat
                        @click="$inertia.get(route('register'))"
                    >
                        {{ $t('web.general.register') }}
                    </q-btn>
                </template>
                <q-btn
                    class="q-ml-lg"
                    round
                    flat
                    dense
                    :icon="mdiMagnify"
                    @click="() => (showSearch = !showSearch)"
                />
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page>
                <q-form @submit.prevent="submit">
                    <q-input
                        v-model="searchForm.q"
                        :class="{
                            hidden: !showSearch && $page.component !== 'Search',
                        }"
                        :placeholder="$t('web.general.search_placeholder')"
                    >
                        <template #prepend>
                            <q-icon
                                class="q-ml-md"
                                name="mdi-magnify"
                            />
                        </template>
                        <template #append>
                            <q-btn
                                class="q-mr-md"
                                flat
                                round
                                icon="mdi-close"
                                @click="() => (searchForm.q = '')"
                            />
                        </template>
                    </q-input>
                </q-form>
                <div
                    :itemscope="props.itemscope"
                    :itemtype="props.itemtype"
                >
                    <slot />
                </div>
            </q-page>
        </q-page-container>

        <q-footer class="bg-grey-8 q-pa-xl">
            <div class="row q-gutter-xl justify-center">
                <div
                    class="col-1 column justify-start items-start content-end text-white"
                >
                    <img
                        alt="Kanojo Logo"
                        src="/images/logo-dark.svg"
                        style="width: 150px; height: 48px"
                        fit="contain"
                    />
                </div>
                <div
                    class="col-1 column justify-start items-start content-start text-white"
                >
                    <h3 class="text-h6 text-weight-bold q-my-none">
                        {{ $t('web.general.pages.general') }}
                    </h3>
                    <Link
                        :href="route('about.index')"
                        class="text-white"
                    >
                        {{ $t('web.general.pages.about') }}
                    </Link>
                    <Link
                        href="#"
                        class="text-white"
                    >
                        {{ $t('web.general.pages.contact_us') }}
                    </Link>
                    <Link
                        class="text-white"
                        :href="route('scribe')"
                    >
                        {{ $t('web.general.pages.api') }}
                    </Link>
                    <a
                        class="text-white"
                        href="https://kanojo1.statuspage.io/"
                    >
                        {{ $t('web.general.pages.status') }}
                    </a>
                </div>
                <div
                    class="col-1 column justify-start items-start content-start text-white"
                >
                    <h3 class="text-h6 text-weight-bold q-my-none">
                        {{ $t('web.general.pages.contribute') }}
                    </h3>
                    <Link
                        class="text-white"
                        :href="route('bible.general')"
                    >
                        {{ $t('web.general.pages.bible') }}
                    </Link>
                    <Link
                        class="text-white"
                        :href="route('movies.create')"
                    >
                        {{ $t('web.general.pages.add_movie') }}
                    </Link>
                    <Link
                        class="text-white"
                        :href="route('movies.create')"
                    >
                        {{ $t('web.general.pages.fill_missing') }}
                    </Link>
                    <Link
                        class="text-white"
                        href="https://hosted.weblate.org/projects/kanojo/website/"
                    >
                        {{ $t('web.general.pages.help_translate') }}
                    </Link>
                </div>
                <div
                    class="col-1 column justify-start items-start content-start text-white"
                >
                    <h3 class="text-h6 text-weight-bold q-my-none">
                        {{ $t('web.general.pages.community') }}
                    </h3>
                    <a
                        class="text-white"
                        href="https://discord.gg/chg5KzTHHp"
                    >
                        {{ $t('web.general.pages.discord') }}
                    </a>
                    <a
                        class="text-white"
                        href="https://github.com/kanojo-db"
                    >
                        {{ $t('web.general.pages.github') }}
                    </a>
                </div>
            </div>
        </q-footer>
    </q-layout>
</template>
