<script setup lang="ts">
import { computed } from 'vue';
import FormSelect from '../forms/FormSelect.vue';

interface Props {
    modelValue: string | null;
    placeholder?: string;
    options?: { label: string; value: string }[];
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Filter Status',
    options: () => [
        { label: 'Semua Status', value: '' },
        { label: 'Published', value: 'published' },
        { label: 'Draft', value: 'draft' },
        { label: 'Archived', value: 'archived' },
    ],
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'filter', value: string): void;
}>();

const proxyValue = computed({
    get: () => props.modelValue || '',
    set: (val) => {
        emit('update:modelValue', val);
        emit('filter', val);
    },
});
</script>

<template>
    <div class="w-full sm:w-48">
        <FormSelect
            v-model="proxyValue"
            :options="options"
            :placeholder="placeholder"
        />
    </div>
</template>
