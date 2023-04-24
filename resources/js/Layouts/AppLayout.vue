<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useDisplay } from 'vuetify';

import DrawerMenu from '@/Components/DrawerMenu.vue';
import Footer from '@/Components/Footer.vue';
import Navbar from '@/Components/Navbar.vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: '',
    },
    itemscope: {
        type: String,
        required: false,
        default: null,
    },
    itemtype: {
        type: String,
        required: false,
        default: null,
    },
});

const showDrawer = ref(false);

const page = usePage();

const form = useForm({});

const resendEmail = () => {
    form.post(route('verification.send'));
};

const isEmailVerified = computed(() => !!page.props.user?.email_verified_at);

const { mdAndUp } = useDisplay();
</script>

<template>
    <v-app>
        <v-layout>
            <v-navigation-drawer
                v-model="showDrawer"
                absolute
                temporary
            >
                <drawer-menu />
            </v-navigation-drawer>

            <navbar v-model:show-drawer="showDrawer" />

            <v-main
                class="flex flex-col"
                :itemscope="props.itemscope"
                :itemtype="props.itemtype"
            >
                <v-banner
                    v-if="!isEmailVerified && page.props.user"
                    :lines="mdAndUp ? 'one' : 'two'"
                    color="warning"
                    text="Your email address is not verified. Please verify it using the link we sent you."
                >
                    <template #actions>
                        <v-btn
                            color="warning"
                            variant="text"
                            @click="resendEmail"
                        >
                            {{ $t('general.resentVerificationLink') }}
                        </v-btn>
                    </template>
                </v-banner>

                <div class="grow">
                    <slot />
                </div>

                <Footer />
            </v-main>
        </v-layout>
    </v-app>
</template>
