<script setup lang="ts">
import type { Person } from '@/types/models';
import type { PropType } from 'vue';

import { DateTime } from 'luxon';
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiGenderFemale from '~icons/mdi/gender-female';
import MdiGenderMale from '~icons/mdi/gender-male';
import MdiNonBinary from '~icons/mdi/gender-non-binary';
import MdiTransgender from '~icons/mdi/gender-transgender';
import MdiHelp from '~icons/mdi/help';

import FlagIcon from '@/Components/FlagIcon.vue';
import { getName, useName } from '@/utils/item';
import { getGender } from '@/utils/misc';

const props = defineProps({
    item: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.item, locale);

const genderIcon = computed(() => {
    switch (props.item.gender?.id) {
        case 1:
            return MdiGenderFemale;
        case 2:
            return MdiGenderMale;
        case 3:
        case 4:
            return MdiTransgender;
        case 5:
            return MdiNonBinary;
        default:
            return MdiHelp;
    }
});
</script>

<template>
    <div class="flex w-full flex-col">
        <div class="mb-4 flex flex-col">
            <div class="flex flex-row items-center gap-2">
                <meta
                    v-if="props.item.gender"
                    itemprop="gender"
                    :content="getGender(props.item)"
                />

                <v-tooltip
                    location="bottom"
                    :text="
                        props.item.gender
                            ? getName(props.item.gender, 'en-US')
                            : 'Unknown'
                    "
                >
                    <template #activator="{ props: tooltipProps }">
                        <v-icon
                            v-bind="tooltipProps"
                            :icon="genderIcon"
                            class="h-8 w-8"
                        />
                    </template>
                </v-tooltip>

                <flag-icon
                    class="mt-1 h-8 w-8"
                    :country="props.item.country"
                />

                <h1 class="line-clamp-2 text-ellipsis text-4xl font-extrabold">
                    {{ name }}
                </h1>

                <v-chip class="ml-2">
                    {{
                        $t('personShow.moviesCount', {
                            number: props.item.movies.total.toLocaleString(),
                        })
                    }}
                </v-chip>
            </div>

            <span
                v-if="name !== props.item.name['ja-JP']"
                class="mt-1 text-lg font-bold"
            >
                {{ props.item.name['ja-JP'] }}
            </span>
        </div>

        <div
            class="grid grid-cols-[1fr_1fr] gap-2 md:grid-cols-[1fr_1fr_1fr] lg:grid-cols-[1fr_1fr_1fr_1fr] xl:grid-cols-[theme(spacing.48)_theme(spacing.48)_theme(spacing.48)_theme(spacing.48)]"
        >
            <div class="xl:w-48">
                <strong>
                    {{ $t('model.birth_date') }}
                </strong>

                <br />

                <meta
                    v-if="props.item.birthdate"
                    itemprop="birthDate"
                    :content="props.item.birthdate"
                />

                <span>
                    {{
                        props.item.birthdate
                            ? DateTime.fromISO(
                                  props.item.birthdate,
                              ).toLocaleString(DateTime.DATE_MED)
                            : $t('general.unknown')
                    }}
                </span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.country') }}
                </strong>

                <br />

                <meta
                    v-if="props.item.country"
                    itemprop="nationality"
                    :content="props.item.country.name"
                />

                <span>
                    {{ props.item.country?.name ?? $t('general.unknown') }}
                </span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.blood_type') }}
                </strong>

                <br />

                <span>{{
                    props.item.blood_type ?? $t('general.unknown')
                }}</span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.height') }}
                </strong>

                <br />

                <meta
                    v-if="props.item.height"
                    itemprop="height"
                    :content="props.item.height.toString()"
                />

                <span>{{
                    props.item.height
                        ? $t('model.unit_cm', {
                              number: props.item.height.toLocaleString(),
                          })
                        : $t('general.unknown')
                }}</span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.bust') }}
                </strong>

                <br />

                <span>{{
                    props.item.bust
                        ? $t('model.unit_cm', {
                              number: props.item.bust.toLocaleString(),
                          })
                        : $t('general.unknown')
                }}</span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.waist') }}
                </strong>

                <br />

                <span>{{
                    props.item.waist
                        ? $t('model.unit_cm', {
                              number: props.item.waist.toLocaleString(),
                          })
                        : $t('general.unknown')
                }}</span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.hips') }}
                </strong>

                <br />

                <span>{{
                    props.item.hip
                        ? $t('model.unit_cm', {
                              number: props.item.hip.toLocaleString(),
                          })
                        : $t('general.unknown')
                }}</span>
            </div>

            <div class="xl:w-48">
                <strong>
                    {{ $t('model.cup_size') }}
                </strong>

                <br />

                <span>
                    {{ props.item.cup_size ?? $t('general.unknown') }}
                </span>
            </div>
        </div>
    </div>
</template>
