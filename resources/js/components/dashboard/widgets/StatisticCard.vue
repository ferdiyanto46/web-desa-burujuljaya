<script setup lang="ts">
import type { Component } from 'vue';

defineProps<{
    title: string;
    value: string | number;
    icon: Component;
    colorClass: string;
}>();
</script>

<template>
    <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex items-center gap-4 transition-all hover:shadow-md">
        <div :class="['flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center', colorClass]">
            <component :is="icon" class="w-6 h-6" aria-hidden="true" />
        </div>
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">{{ title }}</p>
            <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">{{ value }}</p>
        </div>
    </div>
</template>
