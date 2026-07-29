<script setup lang="ts">
import { ADMIN_NAVIGATION } from '@/constants/navigation';
import { useLayoutStore } from '@/stores/layoutStore';
import NavigationDivider from './NavigationDivider.vue';
import SidebarFooter from './SidebarFooter.vue';
import SidebarGroup from './SidebarGroup.vue';
import SidebarHeader from './SidebarHeader.vue';

const layoutStore = useLayoutStore();
</script>

<template>
    <aside 
        class="hidden md:flex flex-col bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-all duration-300 z-20 h-screen sticky top-0"
        :class="layoutStore.desktopSidebarMode === 'expanded' ? 'w-64' : 'w-20'"
        aria-label="Sidebar"
    >
        <SidebarHeader />
        
        <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar py-2">
            <template v-for="(group, index) in ADMIN_NAVIGATION" :key="group.label">
                <SidebarGroup 
                    :group="group" 
                    :collapsed="layoutStore.desktopSidebarMode === 'collapsed'" 
                />
                <NavigationDivider v-if="index < ADMIN_NAVIGATION.length - 1" />
            </template>
        </div>
        
        <SidebarFooter />
    </aside>
</template>

<style scoped>
/* Custom thin scrollbar */
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 4px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #475569;
}
</style>
