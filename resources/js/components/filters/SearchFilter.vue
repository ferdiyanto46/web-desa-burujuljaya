<script setup lang="ts">
import { useDebounceFn } from '@vueuse/core';
import { Search } from 'lucide-vue-next';
import { computed } from 'vue';

interface Props {
    modelValue: string;
    placeholder?: string;
    debounce?: number;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Cari...',
    debounce: 300,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search', value: string): void;
}>();

const proxyValue = computed({
    get: () => props.modelValue,
    set: (val) => {
        emit('update:modelValue', val);
        debouncedEmit(val);
    },
});

const debouncedEmit = useDebounceFn((val: string) => {
    emit('search', val);
}, props.debounce);
</script>

<template>
    <div class="relative flex-1 max-w-sm">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <Search class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
        <input
            type="search"
            v-model="proxyValue"
            :placeholder="placeholder"
            class="block w-full rounded-md border-0 py-1.5 pl-10 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-900 dark:text-gray-100 dark:ring-gray-700 dark:placeholder:text-gray-500 dark:focus:ring-blue-500"
        />
    </div>
</template>
