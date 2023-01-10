<template>
    <q-card
        flat
        bordered
    >
        <q-card-section class="bg-primary text-white row items-center">
            <div class="text-weight-bold text-h6">{{ $t('web.general.pages.settings') }}</div>
        </q-card-section>

        <q-separator />

        <q-list>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        $page.component === 'Settings/Account',
                }"
            >
                <q-item-section>{{ $t('web.settings.account.title') }}</q-item-section>
            </q-item>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        $page.component === 'Settings/Tokens',
                }"
            >
                <q-item-section>{{ $t('web.settings.tokens.title') }}</q-item-section>
            </q-item>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        $page.component === 'Settings/Sessions',
                }"
            >
                <q-item-section>{{ $t('web.settings.sessions.title') }}</q-item-section>
            </q-item>
        </q-list>
    </q-card>
</template>
