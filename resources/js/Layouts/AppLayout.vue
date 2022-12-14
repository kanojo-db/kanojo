<script setup>
import { Inertia } from '@inertiajs/inertia';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import { mdiMagnify, mdiPlus } from '@quasar/extras/mdi-v6';
import { computed, ref } from 'vue';

defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
});

const showSearch = ref(false);

const logout = () => {
    Inertia.post(route('logout'));
};

const searchForm = useForm({
    q: route().params.q || '',
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
</script>

<template>
    <Head :title="title" />

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
                    square
                    dense
                    :icon="mdiPlus"
                    class="q-mr-md"
                    @click="$inertia.get(route('movies.create'))"
                />
                <q-avatar
                    v-if="$page.props.user"
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
                                        route('profile.show', $page.props.user),
                                    )
                                "
                            >
                                <q-item-section class="q-py-md">
                                    <q-item-label class="text-weight-bold">
                                        {{ $page.props.user.name }}
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
                    class="q-ml-md"
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
                <slot />
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
                    <Link class="text-white">
                        {{ $t('web.general.pages.contact_us') }}
                    </Link>
                    <Link
                        class="text-white"
                        :href="route('scribe')"
                    >
                        {{ $t('web.general.pages.api') }}
                    </Link>
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
                        href="#"
                    >
                        {{ $t('web.general.pages.discord') }}
                    </a>
                    <Link
                        class="text-white"
                        href="https://github.com/kanojo-db"
                    >
                        {{ $t('web.general.pages.github') }}
                    </Link>
                </div>
            </div>
        </q-footer>
    </q-layout>
</template>
