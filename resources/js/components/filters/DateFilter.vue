<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    modelValue: string | null;
    placeholder?: string;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Filter Tanggal',
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
    <div class="w-full sm:w-48 relative">
        <input
            type="date"
            v-model="proxyValue"
            class="block w-full rounded-md border-0 py-1.5 px-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700 dark:focus:ring-blue-500"
            :placeholder="placeholder"
        />
    </div>
</template>
