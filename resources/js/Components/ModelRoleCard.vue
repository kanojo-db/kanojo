<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { DateTime } from 'luxon';
import { PropType } from 'vue';
import { useI18n } from 'vue-i18n';

import { Movie, Person } from '@/types/models';
import { useFirstImage, useName } from '@/utils/item';

const props = defineProps({
    movie: {
        type: Object as PropType<Movie>,
        required: true,
    },
    model: {
        type: Object as PropType<Person>,
        required: true,
    },
});

const getHumanReadableDate = (date: string) => {
    return DateTime.fromISO(date).toLocaleString(DateTime.DATE_SHORT);
};

const getModelAge = (date: string) => {
    if (
        props.movie.release_date &&
        DateTime.fromSQL(props.movie.release_date)
    ) {
        const modelDate = DateTime.fromSQL(date)
            .diff(DateTime.fromISO(props.movie.release_date), 'years')
            .toObject();

        return Math.floor(Math.abs(modelDate.years ?? 0));
    }

    return null;
};

const getModelImage = (model: Person) => {
    return useFirstImage(model, 'profile');
};

const locale = useI18n().locale.value;

const name = useName(props.model, locale);
</script>

<template>
    <div
        class="col-4"
        itemprop="actor"
        itemscope
        itemtype="https://schema.org/Person"
    >
        <meta
            itemprop="gender"
            content="https://schema.org/Female"
        />
        <Link :href="$route('models.show', model)">
            <div
                class="row q-pa-md rounded-borders"
                :class="true ? 'bg-pink-1' : 'bg-blue-1'"
            >
                <div class="q-pr-none q-mr-md">
                    <q-avatar
                        size="90px"
                        color="white"
                    >
                        <q-img
                            v-if="getModelImage(model).value"
                            :src="getModelImage(model).value"
                            :ratio="1"
                            fit="cover"
                        />
                        <q-icon
                            v-else
                            name="mdi-help"
                            size="52px"
                            :color="true ? 'pink-1' : 'blue-1'"
                        />
                    </q-avatar>
                </div>
                <div class="col">
                    <div
                        itemprop="name"
                        class="text-h6 q-my-none"
                    >
                        {{ name }}
                    </div>
                    <div
                        v-if="model.birthdate"
                        class="text-body1 q-mt-none"
                    >
                        {{ $t('web.movie.show.born') }}
                        <span
                            itemprop="birthDate"
                            :content="model.birthdate"
                        >
                            {{ getHumanReadableDate(model.birthdate) }}
                        </span>
                        <span
                            v-if="model.birthdate && movie.release_date"
                            class="q-ml-xs"
                        >
                            <i18n-t keypath="web.movie.show.age">
                                <template #age>
                                    {{ getModelAge(model.birthdate) }}
                                </template>
                            </i18n-t>
                        </span>
                    </div>
                    <div
                        v-else
                        class="text-body1 q-mt-none"
                    >
                        {{ $t('web.movie.show.born') }}
                        {{ $t('web.movie.show.unknown_birth_date') }}
                    </div>
                </div>
            </div>
        </Link>
    </div>
</template>
