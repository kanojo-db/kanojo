<template>
    <q-card
        flat
        bordered
    >
        <q-card-section class="bg-primary text-white row items-center">
            <div class="text-weight-bold text-h6">Settings</div>
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
                <q-item-section> Account Settings </q-item-section>
            </q-item>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        $page.component === 'Settings/API',
                }"
            >
                <q-item-section> API Tokens </q-item-section>
            </q-item>
        </q-list>
    </q-card>
</template>
