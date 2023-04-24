<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import { addFont, cleanLocale } from '@/utils/lang';

const i18n = useI18n();

const locale = computed(() => {
    return i18n.locale.value.split('-')[0].toUpperCase();
});

const page = usePage();

const localeList = computed(() => {
    const locales = page.props.languages as {
        name: string;
        code: string;
    }[];

    return locales.sort((a, b) => a.name.localeCompare(b.name));
});

const FallbackLocaleList = computed(() => {
    return [
        {
            name: "None (Don't Fallback)",
            code: 'none',
        },
        ...localeList.value,
    ];
});

const currentLocale = ref(i18n.locale.value);

const currenFallbackLocale = ref(i18n.fallbackLocale.value);

const changeLocaleForm = useForm({
    // eslint-disable-next-line vue/no-ref-object-destructure -- We simply want the initial value, updates are handled by changeLocale()
    locale: currentLocale.value,
});

const changeLocale = () => {
    // If the i18n locale is already the same as the current locale, don't do anything
    if (i18n.locale.value === currentLocale.value) {
        return;
    }

    i18n.locale.value = currentLocale.value;

    changeLocaleForm.locale = currentLocale.value;

    // Skip this if no user is logged in or we're in SSR
    if (!page.props.user || import.meta.env.SSR) {
        return;
    }

    changeLocaleForm.post(route('user.locale.store'), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            changeLocaleForm.reset();
        },
    });
};

const changeFallbackLocale = () => {
    // If the i18n fallback locale is already the same as the current fallback locale, don't do anything
    if (i18n.fallbackLocale.value === currenFallbackLocale.value) {
        return;
    }

    i18n.fallbackLocale.value = currenFallbackLocale.value;

    // If the user does not want fallbacks, use ! at the end of the locale to suppress them.
    // See https://kazupon.github.io/vue-i18n/guide/fallback.html#implicit-fallback-using-locales
    if (currenFallbackLocale.value === 'none') {
        i18n.locale.value = `${i18n.locale.value}!`;
    } else {
        i18n.locale.value = i18n.locale.value.replace('!', '');
    }

    // TODO: Save fallback locale somewhere, ideally in the user's settings
};

// Lookup table for fonts based on language code
const fontLookup: Record<string, string> = {
    'zh-Hans':
        'https://fonts.googleapis.com/css2?family=Noto+Sans+SC:wght@100;200;300;400;500;600;700;800;900&display=swap',
    'zh-Hant':
        'https://fonts.googleapis.com/css2?family=Noto+Sans+TC:wght@100;200;300;400;500;600;700;800;900&display=swap',
};

watch(
    () => i18n.locale.value,
    () => {
        // If we're in SSR, we don't want to mess with fonts.
        if (import.meta.env.SSR) {
            return;
        }

        document.documentElement.lang = cleanLocale(i18n.locale.value);

        // If the lookup table has a font for the current locale, add it to the page (check using keys)
        if (Object.keys(fontLookup).includes(i18n.locale.value)) {
            addFont(fontLookup[i18n.locale.value]);
        }
    },
    {
        immediate: true,
    },
);

watch(
    () => currentLocale.value,
    () => {
        changeLocale();
    },
    {
        immediate: true,
    },
);

watch(
    () => currenFallbackLocale.value,
    () => {
        changeFallbackLocale();
    },
    {
        immediate: true,
    },
);
</script>

<template>
    <v-menu
        open-on-hover
        :close-on-content-click="false"
        offset="8px"
    >
        <template #activator="{ props }">
            <v-btn
                v-bind="props"
                variant="tonal"
                icon
                :text="locale"
                class="rounded-none font-black"
            />
        </template>

        <v-list :width="250">
            <v-list-item>
                <v-list-item-title class="mb-2 font-bold">
                    {{ $t('global.language.defaultLanguage') }}
                </v-list-item-title>

                <v-select
                    v-model="currentLocale"
                    :items="localeList"
                    item-title="name"
                    item-value="code"
                    :aria-label="$t('global.language.defaultLanguage')"
                    hide-details
                />
            </v-list-item>

            <v-list-item>
                <v-list-item-title class="mb-2 font-bold">
                    {{ $t('global.language.fallbackLanguage') }}
                </v-list-item-title>

                <v-select
                    v-model="currenFallbackLocale"
                    :items="FallbackLocaleList"
                    item-title="name"
                    item-value="code"
                    :aria-label="$t('global.language.fallbackLanguage')"
                    hide-details
                />
            </v-list-item>
        </v-list>
    </v-menu>
</template>
