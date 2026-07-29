<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { BreadcrumbItem } from '@/composables/useBreadcrumb';

interface Props {
    items: BreadcrumbItem[];
}

defineProps<Props>();
</script>

<template>
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
            <li>
                <div>
                    <Link :href="route('dashboard')" class="hover:text-gray-700 dark:hover:text-gray-300">
                        <svg class="h-4 w-4 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                        </svg>
                        <span class="sr-only">Home</span>
                    </Link>
                </div>
            </li>
            
            <li v-for="(item, index) in items" :key="index">
                <div class="flex items-center">
                    <svg class="h-4 w-4 shrink-0 text-gray-400 dark:text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                    </svg>
                    <component
                        :is="item.url ? Link : 'span'"
                        :href="item.url"
                        class="ml-2 text-sm font-medium"
                        :class="[
                            item.url 
                                ? 'hover:text-gray-700 dark:hover:text-gray-300' 
                                : 'text-gray-700 dark:text-gray-300'
                        ]"
                        :aria-current="!item.url ? 'page' : undefined"
                    >
                        {{ item.label }}
                    </component>
                </div>
            </li>
        </ol>
    </nav>
</template>
