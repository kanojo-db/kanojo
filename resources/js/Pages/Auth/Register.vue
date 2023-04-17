<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { mdiArrowRight, mdiEye, mdiEyeOff } from '@quasar/extras/mdi-v6';
import { siFacebook, siGithub, siGoogle, siTwitter } from 'simple-icons';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
    name: '',
    email: '',
    password: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Register" />

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
                                {{ $t('web.register.joinHeader') }}
                            </h1>
                            <q-form
                                class="q-px-xl q-pt-xl q-pb-none login-form"
                                @submit.prevent="submit"
                            >
                                <q-input
                                    id="email"
                                    v-model="form.email"
                                    class="q-mb-sm"
                                    type="email"
                                    hint="This will be used to log in to your account."
                                    filled
                                    required
                                    bottom-slots
                                    label="Email"
                                    :error="!!form?.errors?.email"
                                    :error-message="form.errors.email"
                                />

                                <q-input
                                    id="name"
                                    v-model="form.name"
                                    class="q-mb-sm"
                                    type="text"
                                    hint="This will be used to identify you on the site."
                                    required
                                    filled
                                    bottom-slots
                                    autocomplete="name"
                                    label="Username"
                                    :error="!!form?.errors?.name"
                                    :error-message="form.errors.name"
                                />

                                <q-input
                                    id="password"
                                    v-model="form.password"
                                    class="q-mb-md"
                                    :type="showPassword ? 'text' : 'password'"
                                    filled
                                    hint="Your password must be at least 8 characters long."
                                    required
                                    bottom-slots
                                    autocomplete="new-password"
                                    label="Password"
                                    :error="!!form?.errors?.password"
                                    :error-message="form.errors.password"
                                >
                                    <template #append>
                                        <q-icon
                                            :name="
                                                showPassword
                                                    ? mdiEye
                                                    : mdiEyeOff
                                            "
                                            class="cursor-pointer"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                        />
                                    </template>
                                </q-input>

                                <q-card-actions>
                                    <q-btn
                                        class="full-width"
                                        color="primary"
                                        label="Register"
                                        type="submit"
                                        :disabled="form.processing"
                                    />
                                </q-card-actions>
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
                                    {{ $t('web.register.alreadyUser') }}
                                    <Link
                                        :href="route('login')"
                                        class="underline text-sm"
                                    >
                                        {{ $t('web.general.login') }}
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
                        Ready to explore the world of Japanese adult video and
                        gravure?
                    </h2>
                    <p class="text-h5 text-weight-medium">
                        Join Kanojo and gain access to our comprehensive
                        database of titles and models, all contributed by movie
                        enthusiasts like you.
                    </p>
                    <p class="text-h5 text-weight-medium">Why join us?</p>
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
                                    Share your knowledge and help us build the
                                    most comprehensive database of Japanese
                                    Adult Video and Gravure movies by adding or
                                    correcting information.
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
                                    for your media server and level up your
                                    movie collection.
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
                        Join us now and unlock the full potential of Kanojo -
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
