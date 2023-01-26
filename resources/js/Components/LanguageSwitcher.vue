<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { useI18n } from 'vue-i18n';

const i18n = useI18n();

const locale = computed(() => {
    return i18n.locale.value.split('-')[0].toUpperCase();
});

const localeList = ref([
    {
        label: 'English (en-US)',
        value: 'en-US',
    },
    {
        label: 'French (fr-FR)',
        value: 'fr-FR',
    },
    {
        label: 'Spanish (es-ES)',
        value: 'es-ES',
    },
]);

const currentLocale = ref({
    label:
        localeList.value.find((locale) => locale.value === i18n.locale.value)
            ?.label ?? 'Unknown',
    value: i18n.locale.value,
});

const changeLocaleForm = useForm({
    locale: currentLocale.value.value,
});

const changeLocale = () => {
    // Change the locale on the user's side early, to provide a better UX
    i18n.locale.value = currentLocale.value.value;

    changeLocaleForm.locale = currentLocale.value.value;

    changeLocaleForm.post(route('user.locale.store'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            changeLocaleForm.reset();
        },
    });
};
</script>

<template>
    <q-btn
        outline
        square
        class="q-mr-lg"
        :label="locale"
    >
        <q-menu
            anchor="bottom middle"
            self="top middle"
        >
            <q-list
                class="q-pa-md"
                style="min-width: 300px"
            >
                <q-select
                    v-model="currentLocale"
                    :options="localeList"
                    label="Translations"
                    filled
                    @update:model-value="changeLocale"
                />
            </q-list>
        </q-menu>
    </q-btn>
</template>
