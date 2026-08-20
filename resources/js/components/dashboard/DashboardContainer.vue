<script setup lang="ts">
import { onMounted } from 'vue';
import { useLayoutStore } from '@/stores/layoutStore';
import LoadingOverlay from '../feedback/LoadingOverlay.vue';
import ResponsiveDrawer from './ResponsiveDrawer.vue';
import Sidebar from './Sidebar.vue';
import Topbar from './Topbar.vue';

const layoutStore = useLayoutStore();

onMounted(() => {
    layoutStore.initTheme();
    layoutStore.initSidebar();
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors">
        <LoadingOverlay />
        
        <ResponsiveDrawer />
        
        <div class="flex h-screen overflow-hidden">
            <Sidebar />
            
            <div class="flex flex-col flex-1 w-full min-w-0 overflow-y-auto overflow-x-hidden focus:outline-none">
                <Topbar />
                
                <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-800 z-10">
                    <div class="px-4 py-6 sm:px-6 lg:px-8 max-w-7xl mx-auto w-full">
                        <slot name="header" />
                    </div>
                </header>
                
                <main class="flex-1 py-8 w-full">
                    <div class="px-4 sm:px-6 lg:px-8 mx-auto max-w-7xl">
                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
