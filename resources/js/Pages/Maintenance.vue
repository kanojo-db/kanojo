<template>
    <q-layout>
        <q-header height-hint="64">
            <q-toolbar class="bg-grey-8 q-py-sm q-px-md">
                <img
                    alt="Kanojo Logo"
                    src="/images/logo-dark.svg"
                    style="width: 150px; height: 48px"
                    fit="contain"
                />
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page class="column justify-center">
                <div class="maintenance-message q-ma-xl justify-center">
                    <h1 class="q-mt-none text-h3 text-weight-bold">
                        Maintenance in Progress - We'll be back shortly!
                    </h1>
                    <p class="q-mb-none text-h4 text-weight-regular q-mb-md">
                        Kanojo is currently undergoing scheduled maintenance to
                        enhance your experience.
                    </p>
                    <p class="q-mb-none text-h4 text-weight-regular q-mb-md">
                        If the website is offline longer than expected or this
                        maintenance wasn't planned, keep an eye on our
                        <a
                            class="underline"
                            href="https://twitter.com/kanojodb"
                            >Twitter</a
                        >
                        for updates on the situation.
                    </p>
                    <p class="q-mb-none text-h4 text-weight-regular q-mb-lg">
                        We apologize for any inconvenience this may cause.
                    </p>
                </div>
            </q-page>
        </q-page-container>
    </q-layout>
</template>

<style lang="scss">
.maintenance-message {
    max-width: 1300px;
}
</style>
