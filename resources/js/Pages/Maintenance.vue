<template>
    <v-app>
        <v-layout>
            <v-app-bar
                flat
                class="bg-primary"
            >
                <v-app-bar-title class="mr-2 max-w-[130px]">
                    <img
                        alt="Kanojo"
                        src="/images/logo-dark.svg"
                        class="h-[36px] w-[130px] object-contain"
                    />
                </v-app-bar-title>
            </v-app-bar>

            <v-main>
                <v-container class="prose">
                    <h1>
                        {{ $t('general.maintenance.title') }}
                    </h1>

                    <p>
                        {{ $t('general.maintenance.description1') }}
                    </p>

                    <p>{{ $t('general.maintenance.description3') }}</p>
                </v-container>
            </v-main>
        </v-layout>
    </v-app>
</template>
