<script setup lang="ts">
import { Icons } from '@/constants/icons';
import { useLayoutStore } from '@/stores/layoutStore';
import TopbarActions from './TopbarActions.vue';

const layoutStore = useLayoutStore();
</script>

<template>
    <header class="sticky top-0 z-30 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white/80 backdrop-blur-md px-4 sm:gap-x-6 sm:px-6 lg:px-8 dark:bg-gray-900/80 dark:border-gray-800 transition-colors">
        <!-- Mobile Sidebar Toggle -->
        <button 
            type="button" 
            class="-m-2.5 p-2.5 text-gray-700 md:hidden dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors"
            @click="layoutStore.toggleMobileDrawer()"
            aria-label="Buka navigasi"
        >
            <component :is="Icons.MenuIcon" class="h-6 w-6" aria-hidden="true" />
        </button>

        <!-- Separator -->
        <div class="h-6 w-px bg-gray-200 md:hidden dark:bg-gray-700" aria-hidden="true"></div>

        <TopbarActions />
    </header>
</template>
