<script setup lang="ts">
import { Icons } from '@/constants/icons';

defineProps<{
    modelValue: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
    <div class="relative w-full max-w-lg">
        <label for="search" class="sr-only">Cari</label>
        <div class="relative">
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <component :is="Icons.SearchIcon" class="h-5 w-5 text-gray-400" aria-hidden="true" />
            </div>
            <input 
                id="search" 
                name="search" 
                :value="modelValue"
                @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
                class="block w-full rounded-full border-0 py-2.5 pl-10 pr-4 text-gray-900 dark:text-white ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 dark:bg-gray-800 sm:text-sm sm:leading-6" 
                :placeholder="placeholder || 'Ketik untuk mencari...'" 
                type="search" 
            />
        </div>
    </div>
</template>

