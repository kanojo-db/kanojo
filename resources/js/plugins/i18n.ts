import messages from '@intlify/unplugin-vue-i18n/messages';
import { createI18n } from 'vue-i18n';

function getI18nPlugin(locale: string) {
    return createI18n({
        legacy: false,
        locale: locale,
        fallbackLocale: 'en-US',
        messages: messages,
    });
}

export default getI18nPlugin;
