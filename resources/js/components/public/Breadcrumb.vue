<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icons } from '@/constants/icons';
import { route } from '@/lib/route';

defineProps<{
    items: Array<{
        name: string;
        route?: string;
    }>
}>();
</script>

<template>
    <nav class="flex text-sm text-gray-500 dark:text-gray-400 mb-6 font-medium" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <Link :href="route('home')" class="inline-flex items-center hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                    <component :is="Icons.HomeIcon" class="w-4 h-4 mr-2" />
                    Beranda
                </Link>
            </li>
            <li v-for="(item, index) in items" :key="index">
                <div class="flex items-center">
                    <component :is="Icons.ChevronRightIcon" class="w-4 h-4 text-gray-400 mx-1" />
                    <Link 
                        v-if="item.route && index < items.length - 1" 
                        :href="route(item.route)" 
                        class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                    >
                        {{ item.name }}
                    </Link>
                    <span v-else class="text-gray-800 dark:text-gray-200">{{ item.name }}</span>
                </div>
            </li>
        </ol>
    </nav>
</template>
