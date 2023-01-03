<template>
    <q-card
        flat
        bordered
    >
        <q-card-section class="bg-primary text-white row items-center">
            <div class="text-weight-bold text-h6">General</div>
        </q-card-section>

        <q-separator />

        <q-list>
            <q-item
                clickable
                :class="{
                    'text-weight-bold text-primary':
                        $page.component === 'Bible/Generalt',
                }"
            >
                <q-item-section> General Tips </q-item-section>
            </q-item>
        </q-list>
    </q-card>
</template>
