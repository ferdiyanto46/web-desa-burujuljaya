<script setup lang="ts">
import { Icons } from '@/constants/icons';
import { useLayoutStore } from '@/stores/layoutStore';

const layoutStore = useLayoutStore();
</script>

<template>
    <div class="p-4 border-t border-gray-200 dark:border-gray-800 shrink-0 mt-auto">
        <button 
            @click="layoutStore.toggleDarkMode()"
            class="flex items-center gap-3 w-full p-2 text-sm font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-800 transition-colors"
            :class="{ 'justify-center': layoutStore.desktopSidebarMode === 'collapsed' && !layoutStore.isMobileDrawerOpen }"
            aria-label="Toggle Theme"
        >
            <component :is="layoutStore.isDark ? Icons.SunIcon : Icons.MoonIcon" class="w-5 h-5 shrink-0" />
            <span v-if="layoutStore.desktopSidebarMode === 'expanded' || layoutStore.isMobileDrawerOpen" class="truncate">
                {{ layoutStore.isDark ? 'Light Mode' : 'Dark Mode' }}
            </span>
        </button>
    </div>
</template>
