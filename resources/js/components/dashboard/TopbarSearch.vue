<script setup lang="ts">
import { Icons } from '@/constants/icons';
</script>

<template>
    <div class="relative w-full max-w-md hidden sm:block">
        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
            <component :is="Icons.SearchIcon" class="h-4 w-4 text-gray-400" aria-hidden="true" />
        </div>
        <input
            type="text"
            placeholder="Ketik '/' untuk pencarian global..."
            class="block w-full rounded-lg border-0 py-1.5 pl-9 pr-3 text-gray-900 ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 dark:bg-gray-800 dark:text-white dark:ring-gray-700 dark:focus:ring-blue-500"
            aria-label="Pencarian Global"
        />
        <div class="absolute inset-y-0 right-0 flex items-center pr-2">
            <kbd class="hidden sm:inline-flex items-center rounded border border-gray-200 px-2 py-0.5 text-xs font-sans font-medium text-gray-400 dark:border-gray-700 dark:text-gray-500">/</kbd>
        </div>
    </div>
</template>
