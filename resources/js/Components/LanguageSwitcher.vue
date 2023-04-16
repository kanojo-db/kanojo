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

const FallbackLocaleList = computed(() => {
    return [
        {
            label: "None (Don't Fallback)",
            value: 'none',
        },
        ...localeList.value,
    ];
});

const currentLocale = ref({
    label:
        localeList.value.find(
            (locale) => locale.value === i18n.locale.value.replace('!', ''),
        )?.label ?? 'Unknown',
    value: i18n.locale.value,
});

const currenFallbacktLocale = ref({
    label:
        localeList.value.find(
            (locale) => locale.value === i18n.fallbackLocale.value,
        )?.label ?? 'Unknown',
    value: i18n.fallbackLocale.value,
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

const changeFallbackLocale = () => {
    // Change the locale on the user's side early, to provide a better UX
    i18n.fallbackLocale.value = currenFallbacktLocale.value.value;

    // If the user does not want fallbacks, use ! at the end of the locale to suppress them.
    // See https://kazupon.github.io/vue-i18n/guide/fallback.html#implicit-fallback-using-locales
    if (currenFallbacktLocale.value.value === 'none') {
        i18n.locale.value = `${i18n.locale.value}!`;
    } else {
        i18n.locale.value = i18n.locale.value.replace('!', '');
    }

    // TODO: Save fallback locale somewhere
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
                    class="q-mb-sm"
                    :options="localeList"
                    label="Default Language"
                    filled
                    @update:model-value="changeLocale"
                />

                <q-select
                    v-model="currenFallbacktLocale"
                    :options="FallbackLocaleList"
                    label="Fallback Language"
                    filled
                    @update:model-value="changeFallbackLocale"
                />
            </q-list>
        </q-menu>
    </q-btn>
</template>
