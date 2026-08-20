<script setup lang="ts">
import type { Component } from 'vue';
import { Icons } from '@/constants/icons';

defineProps<{
    title: string;
    message?: string;
    icon?: Component;
}>();
</script>

<template>
    <div class="flex flex-col items-center justify-center py-16 px-4 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border border-gray-100 dark:border-gray-800">
        <component :is="icon || Icons.FolderIcon" class="w-16 h-16 text-gray-300 dark:text-gray-600 mb-4" />
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">{{ title }}</h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm text-center">
            {{ message || 'Belum ada data yang dapat ditampilkan.' }}
        </p>
        <slot name="action" />
    </div>
</template>
