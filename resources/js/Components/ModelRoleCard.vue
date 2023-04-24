<script setup lang="ts">
import type { Movie, MoviePersonPivot, Person } from '@/types/models';
import type { PropType } from 'vue';

import { Link } from '@inertiajs/vue3';
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
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
    model: {
        type: Object as PropType<Person & MoviePersonPivot>,
        required: true,
    },
});

const locale = useI18n().locale.value;

const name = useName(props.model, locale);

const genderIcon = computed(() => {
    switch (props.model.gender?.id) {
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
    <div
        class="w-full"
        itemprop="actor"
        itemscope
        itemtype="https://schema.org/Person"
    >
        <Link :href="route('models.show', { model: props.model.slug })">
            <div
                class="flex flex-row rounded-lg bg-stone-200 p-4 shadow-md dark:bg-stone-700"
            >
                <div class="mr-4 h-24">
                    <div
                        class="avatar"
                        :class="{ placeholder: !model.poster }"
                    >
                        <div
                            class="aspect-square w-24 rounded-full bg-stone-200 text-stone-300 ring ring-stone-50 ring-offset-2 dark:bg-stone-600 dark:text-stone-500 dark:ring-stone-600"
                        >
                            <v-avatar
                                :image="model.poster?.original_url"
                                :icon="MdiHelp"
                                :size="96"
                                :alt="name"
                                variant="tonal"
                            />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col justify-start">
                    <div class="flex flex-row items-center">
                        <meta
                            itemprop="name"
                            :content="name"
                        />

                        <div class="text-lg font-semibold">
                            {{ name }}
                        </div>

                        <meta
                            v-if="props.model.gender"
                            itemprop="gender"
                            :content="getGender(props.model)"
                        />

                        <v-tooltip
                            location="bottom"
                            :text="
                                props.model.gender
                                    ? getName(props.model.gender, 'en-US')
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

                        <meta
                            v-if="props.model.country"
                            itemprop="nationality"
                            :content="props.model.country.name"
                        />

                        <flag-icon
                            class="h-8 w-8"
                            :country="props.model.country"
                        />
                    </div>

                    <div v-if="props.model.birthdate">
                        {{ $t('movie.show.born') }}
                        <span
                            itemprop="birthDate"
                            :content="props.model.birthdate"
                        >
                            {{
                                DateTime.fromISO(
                                    props.model.birthdate,
                                ).toLocaleString(DateTime.DATE_SHORT)
                            }}
                        </span>

                        <span
                            v-if="props.model.pivot.age"
                            class="ml-1"
                        >
                            <i18n-t keypath="movie.show.age">
                                <template #age>
                                    {{ props.model.pivot.age }}
                                </template>
                            </i18n-t>
                        </span>
                    </div>

                    <div v-else>
                        {{ $t('movie.show.born') }}
                        {{ $t('movie.show.unknown_birth_date') }}
                    </div>

                    <div
                        v-if="
                            props.model.height ||
                            props.model.bust ||
                            props.model.waist ||
                            props.model.hip
                        "
                    >
                        {{
                            $t('movie.show.measurements', {
                                height: props.model.height,
                                bust: props.model.bust,
                                waist: props.model.waist,
                                hip: props.model.hip,
                            })
                        }}
                    </div>
                </div>
            </div>
        </Link>
    </div>
</template>
