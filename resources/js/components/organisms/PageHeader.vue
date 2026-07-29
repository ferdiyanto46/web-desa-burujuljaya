<script setup lang="ts">
import AppBreadcrumb from '@/components/molecules/AppBreadcrumb.vue';
import type { BreadcrumbItem } from '@/composables/useBreadcrumb';

interface Props {
    title: string;
    description?: string;
    breadcrumbs?: BreadcrumbItem[];
}

defineProps<Props>();
</script>

<template>
    <div class="mb-8">
        <AppBreadcrumb v-if="breadcrumbs && breadcrumbs.length > 0" :items="breadcrumbs" class="mb-4" />
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight dark:text-white">
                    {{ title }}
                </h1>
                <p v-if="description" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ description }}
                </p>
            </div>
            <div v-if="$slots.actions" class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none space-x-3 flex">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
