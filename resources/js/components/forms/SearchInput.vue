<script setup lang="ts">
import { watch } from 'vue';
import { useDebounce } from '@/composables/useDebounce';

interface Props {
    modelValue: string;
    placeholder?: string;
    delay?: number;
}

const props = withDefaults(defineProps<Props>(), {
    placeholder: 'Cari...',
    delay: 300,
});

const emit = defineEmits<{
    'update:modelValue': [value: string];
    search: [value: string];
}>();

const localValue = useDebounce(props.modelValue, props.delay);

watch(localValue, (val) => {
    emit('update:modelValue', val);
    emit('search', val);
});

watch(() => props.modelValue, (newVal) => {
    if (newVal !== localValue.value) {
        localValue.value = newVal;
    }
});
</script>

<template>
    <div class="relative w-full max-w-sm">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" />
            </svg>
        </div>
        <input
            type="text"
            v-model="localValue"
            :placeholder="placeholder"
            class="block w-full rounded-md border border-gray-300 bg-white py-2 pl-10 pr-3 text-sm placeholder-gray-500 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:border-blue-400 dark:focus:ring-blue-400"
        />
        <button
            v-if="localValue"
            type="button"
            @click="localValue = ''"
            class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-500"
        >
            <span class="sr-only">Clear search</span>
            <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
            </svg>
        </button>
    </div>
</template>
