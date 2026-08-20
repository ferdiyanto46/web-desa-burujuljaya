<script setup lang="ts">
import { Icons } from '@/constants/icons';
import { useLayoutStore } from '@/stores/layoutStore';
import Logo from './Logo.vue';

const layoutStore = useLayoutStore();
</script>

<template>
    <div 
        class="flex items-center border-b border-gray-200 dark:border-gray-800 shrink-0 h-16 transition-all duration-300"
        :class="layoutStore.desktopSidebarMode === 'collapsed' && !layoutStore.isMobileDrawerOpen ? 'justify-center px-2' : 'justify-between px-4'"
    >
        <Logo v-show="!(layoutStore.desktopSidebarMode === 'collapsed' && !layoutStore.isMobileDrawerOpen)" :collapsed="layoutStore.desktopSidebarMode === 'collapsed' && !layoutStore.isMobileDrawerOpen" />
        
        <button
            v-if="!layoutStore.isMobileDrawerOpen"
            @click="layoutStore.toggleDesktopSidebar()"
            class="hidden md:flex p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"
            aria-label="Toggle Sidebar"
        >
            <component 
                :is="layoutStore.desktopSidebarMode === 'collapsed' ? Icons.ChevronRightIcon : Icons.MenuIcon" 
                class="w-5 h-5" 
            />
        </button>
        
        <button
            v-else
            @click="layoutStore.closeMobileDrawer()"
            class="md:hidden p-1.5 rounded-lg text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 transition-colors"
            aria-label="Close Sidebar"
        >
            <component :is="Icons.XIcon" class="w-5 h-5" />
        </button>
    </div>
</template>
