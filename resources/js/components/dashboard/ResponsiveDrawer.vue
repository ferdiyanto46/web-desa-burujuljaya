<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { DialogRoot, DialogPortal, DialogOverlay, DialogContent } from 'reka-ui';
import { computed } from 'vue';
import { ADMIN_NAVIGATION } from '@/constants/navigation';
import { useLayoutStore } from '@/stores/layoutStore';
import NavigationDivider from './NavigationDivider.vue';
import SidebarFooter from './SidebarFooter.vue';
import SidebarGroup from './SidebarGroup.vue';
import SidebarHeader from './SidebarHeader.vue';

const layoutStore = useLayoutStore();
const page = usePage();

const filteredNavigation = computed(() => {
    const userRoles: string[] = (page.props.auth as any)?.roles || [];
    const isSuperAdmin = userRoles.includes('super-admin');

    return ADMIN_NAVIGATION
        .map(group => {
            if (group.roles && !group.roles.some(r => userRoles.includes(r)) && !isSuperAdmin) {
                return null;
            }

            const filteredItems = group.items.filter(item => {
                if (item.hidden) {
                    return false;
                }
                if (!item.roles || item.roles.length === 0) {
                    return true;
                }
                return isSuperAdmin || item.roles.some(r => userRoles.includes(r));
            });

            if (filteredItems.length === 0) {
                return null;
            }

            return {
                ...group,
                items: filteredItems,
            };
        })
        .filter(Boolean) as typeof ADMIN_NAVIGATION;
});
</script>

<template>
    <DialogRoot :open="layoutStore.isMobileDrawerOpen" @update:open="layoutStore.closeMobileDrawer">
        <DialogPortal>
            <DialogOverlay class="fixed inset-0 bg-black/50 z-40 md:hidden backdrop-blur-sm transition-opacity" />
            <DialogContent 
                class="fixed inset-y-0 left-0 z-50 w-64 bg-white dark:bg-gray-900 shadow-xl flex flex-col md:hidden focus:outline-none transition-transform duration-300 ease-in-out data-[state=open]:translate-x-0 data-[state=closed]:-translate-x-full"
                aria-label="Mobile Navigation Drawer"
            >
                <SidebarHeader />
                
                <div class="flex-1 overflow-y-auto overflow-x-hidden custom-scrollbar py-2">
                    <template v-for="(group, index) in filteredNavigation" :key="group.label">
                        <SidebarGroup 
                            :group="group" 
                            :collapsed="false" 
                        />
                        <NavigationDivider v-if="index < filteredNavigation.length - 1" />
                    </template>
                </div>
                
                <SidebarFooter />
            </DialogContent>
        </DialogPortal>
    </DialogRoot>
</template>

<style scoped>
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
