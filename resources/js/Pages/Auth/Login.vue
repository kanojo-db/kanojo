<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { mdiArrowRight } from '@quasar/extras/mdi-v6';
import { siFacebook, siGithub, siGoogle, siTwitter } from 'simple-icons';

const props = defineProps({
    status: {
        type: String,
        default: '',
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.transform((data) => ({
        ...data,
        remember: form.remember ? 'on' : '',
    })).post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />

    <q-layout view="hHh lpR fFf">
        <q-page-container>
            <q-page class="bg-grey-8 row no-wrap items-stretch">
                <div
                    flat
                    bordered
                    class="bg-grey-1 col-5 column flex justify-center items-center"
                >
                    <div class="self-start bg-grey-2 full-width q-px-md">
                        <q-img
                            src="/images/logo-light.svg"
                            ratio="2"
                            position="50% 50%"
                            fit="contain"
                            width="10em"
                        />
                    </div>
                    <div
                        class="row col-grow justify-center items-center full-width"
                    >
                        <div class="q-mx-xl column items-center">
                            <h1
                                class="text-center text-h3 text-grey-9 text-weight-bolder q-mt-none q-mb-md q-mx-xl"
                            >
                                {{ $t('web.login.welcomeBack') }}
                            </h1>

                            <q-form
                                class="column q-px-xl q-pt-xl q-pb-none login-form"
                                @submit.prevent="submit"
                            >
                                <div
                                    v-if="status"
                                    class="q-mb-md text-white bg-grey-6 q-pa-md rounded-borders"
                                >
                                    {{ status }}
                                </div>

                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    class="q-mb-sm"
                                    type="email"
                                    label="Email"
                                    filled
                                    required
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />

                                <q-input
                                    id="password"
                                    v-model="form.password"
                                    type="password"
                                    label="Password"
                                    filled
                                    required
                                    autocomplete="current-password"
                                    :error="!!form?.errors?.password"
                                    :error-message="form.errors.password"
                                />

                                <q-checkbox
                                    v-model="form.remember"
                                    name="remember"
                                    label="Remember me"
                                />

                                <q-space />

                                <q-btn
                                    color="primary"
                                    :label="$t('web.login.submit')"
                                    type="submit"
                                    class="full-width q-mt-md"
                                    :disabled="form.processing"
                                />

                                <Link
                                    :href="route('password.request')"
                                    class="underline text-right q-mt-sm"
                                >
                                    Forgot your password?
                                </Link>
                            </q-form>

                            <div class="content q-py-md">
                                <p class="or q-ma-none text-grey-5">or</p>
                            </div>

                            <div class="social-buttons q-mb-md q-gutter-x-md">
                                <q-btn
                                    round
                                    outline
                                    :style="{
                                        color: `#${siGoogle.hex}`,
                                    }"
                                    :icon="siGoogle.path"
                                    :disabled="form.processing"
                                    @click="
                                        route('login.provider', {
                                            provider: 'google',
                                        })
                                    "
                                />
                                <q-btn
                                    round
                                    outline
                                    :style="{
                                        color: `#${siGithub.hex}`,
                                    }"
                                    :icon="siGithub.path"
                                    :disabled="form.processing"
                                    @click="
                                        route('login.provider', {
                                            provider: 'github',
                                        })
                                    "
                                />
                            </div>

                            <div>
                                <div>
                                    {{ $t('web.login.needAccount') }}
                                    <Link
                                        :href="route('login')"
                                        class="underline text-sm"
                                    >
                                        {{ $t('web.general.register') }}
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="col-5 column items-start justify-center text-white q-pa-xl"
                >
                    <h2 class="text-h4 text-weight-bold">
                        Already a member? Sign in to explore our comprehensive
                        database of titles and models.
                    </h2>
                    <p class="text-h5 text-weight-medium">Why login?</p>
                    <q-list dark>
                        <q-item>
                            <q-item-section avatar>
                                <q-icon :name="mdiArrowRight" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label
                                    class="text-weight-bold text-body1"
                                >
                                    Contribute to the database
                                </q-item-label>
                                <q-item-label class="text-body2">
                                    Help us improve our database by adding or
                                    correcting information about movies and
                                    models.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section avatar>
                                <q-icon :name="mdiArrowRight" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label
                                    class="text-weight-bold text-body1"
                                >
                                    Enhance your media server collection
                                </q-item-label>
                                <q-item-label class="text-body2">
                                    Use our plugins to scrape accurate metadata
                                    for your media server and enhance your movie
                                    collection with accurate information.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section avatar>
                                <q-icon :name="mdiArrowRight" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label
                                    class="text-weight-bold text-body1"
                                >
                                    Keep track of your favorites
                                </q-item-label>
                                <q-item-label class="text-body2">
                                    Easily track the titles you own, add titles
                                    to your wishlist, and mark your favorites
                                    for quick access.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                        <q-item>
                            <q-item-section avatar>
                                <q-icon :name="mdiArrowRight" />
                            </q-item-section>
                            <q-item-section>
                                <q-item-label
                                    class="text-weight-bold text-body1"
                                >
                                    Discover your favorite performers
                                </q-item-label>
                                <q-item-label class="text-body2">
                                    Our powerful search tools allow you to find
                                    titles based on various performer features,
                                    such as age, measurements, contents of the
                                    movie, and more.
                                </q-item-label>
                            </q-item-section>
                        </q-item>
                    </q-list>

                    <p class="text-body1 q-mt-sm">
                        Sign in now and unlock the full potential of Kanojo -
                        your Japanese Adult Video and Gravure movie database!
                    </p>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<style lang="scss" scoped>
.login-form {
    max-width: 400px;
    width: 400px;
}

.content {
    margin: 0px auto;
    width: 300px;
}

.or {
    text-align: center;
    background: linear-gradient($grey-3 0 0) left,
        linear-gradient($grey-3 0 0) right;
    background-size: 40% 1px;
    background-repeat: no-repeat;
}
</style>
