<script setup lang="ts">
import type { Movie, Person } from '@/types/models';
import type { PropType } from 'vue';

import { router, useForm, usePage } from '@inertiajs/vue3';
import { computed, ref, watch } from 'vue';
import { useI18n } from 'vue-i18n';

import MdiClose from '~icons/mdi/close';

import useRouteStore from '@/stores/route';
import { getItemRouteParams, getName, useTitle } from '@/utils/item';

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    item: {
        type: Object as PropType<Movie>,
        required: true,
    },
});

const emit = defineEmits<{
    (event: 'update:modelValue', value: boolean): void;
}>();

const { locale } = useI18n();

const routeStore = useRouteStore();

const title = useTitle(props.item, locale.value);

const page = usePage();

const models = computed<Person[]>(() => {
    return page.props.models as Person[];
});

const addCastMemberForm = useForm<{
    model_id: number | null;
}>({
    model_id: null,
});

const modelSearch = ref<string>('');
const modelSearchLoading = ref<boolean>(false);

const submit = () => {
    addCastMemberForm.post(
        route(`movies.cast.store`, {
            ...getItemRouteParams(props.item),
        }),
        {
            preserveScroll: true,
            onSuccess: () => {
                addCastMemberForm.reset();

                emit('update:modelValue', false);

                router.reload({
                    preserveScroll: true,
                    preserveState: true,
                    only: ['item'],
                });
            },
        },
    );
};

watch(modelSearch, (value) => {
    modelSearchLoading.value = true;

    router.get(
        route(routeStore.current, {
            ...routeStore.params,
            model: value,
        }),
        {},
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            only: ['models'],
            onFinish: () => {
                modelSearchLoading.value = false;
            },
        },
    );
});
</script>

<template>
    <v-card>
        <v-card-title
            class="flex flex-row items-center bg-primary text-stone-50"
        >
            <div class="line-clamp-1 text-ellipsis text-xl font-extrabold">
                {{ $t('dialogs.addCast.title', { itemTitle: title }) }}
            </div>

            <v-spacer />

            <v-btn
                :icon="MdiClose"
                variant="text"
                @click="emit('update:modelValue', false)"
            />
        </v-card-title>

        <v-divider />

        <v-form @submit.prevent="submit">
            <v-card-text>
                <v-label
                    for="format"
                    :text="$t('dialog.addCast.name')"
                />

                <v-autocomplete
                    v-model="addCastMemberForm.model_id"
                    v-model:search="modelSearch"
                    :loading="modelSearchLoading"
                    :items="models"
                    name="studio"
                    item-value="id"
                    autocomplete="off"
                    :item-title="(item: Person) => getName(item, locale)"
                    clearable
                    persistent-clear
                    :error-messages="addCastMemberForm.errors.model_id"
                />
            </v-card-text>

            <v-card-actions>
                <v-spacer />

                <v-btn
                    type="submit"
                    color="primary"
                    :loading="addCastMemberForm.processing"
                    :text="
                        addCastMemberForm.processing
                            ? `${addCastMemberForm.progress?.percentage}%`
                            : $t('general.saveChanges')
                    "
                />
            </v-card-actions>
        </v-form>
    </v-card>
</template>
