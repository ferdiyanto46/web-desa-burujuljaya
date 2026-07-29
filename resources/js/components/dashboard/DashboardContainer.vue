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
                <slot />
            </div>
        </div>
    </div>
</template>
