<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from 'reka-ui';
import { computed } from 'vue';
import { Icons } from '@/constants/icons';
import { route } from '@/lib/route';
import { useAuthStore } from '@/stores/authStore';

const authStore = useAuthStore();

const userInitials = computed(() => {
    const name = authStore.currentUser?.name || 'Admin';

    return name.substring(0, 2).toUpperCase();
});

const handleLogout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <DropdownMenuRoot>
        <DropdownMenuTrigger class="flex items-center gap-2 rounded-full focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500" aria-label="Menu Profil">
            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold ring-2 ring-white dark:ring-gray-900">
                {{ userInitials }}
            </div>
            <div class="hidden sm:flex flex-col items-start text-left">
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200 leading-none">
                    {{ authStore.currentUser?.name || 'Administrator' }}
                </span>
                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 leading-none">
                    {{ authStore.currentUser?.email || 'admin@example.com' }}
                </span>
            </div>
            <component :is="Icons.ChevronDownIcon" class="w-4 h-4 text-gray-500 dark:text-gray-400 hidden sm:block ml-1" />
        </DropdownMenuTrigger>
        
        <DropdownMenuContent 
            class="z-50 min-w-[200px] bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-1 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2"
            align="end"
            :sideOffset="8"
        >
            <DropdownMenuLabel class="px-3 py-2 sm:hidden">
                <div class="font-medium text-gray-900 dark:text-white">{{ authStore.currentUser?.name || 'Administrator' }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">{{ authStore.currentUser?.email || 'admin@example.com' }}</div>
            </DropdownMenuLabel>
            
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1 sm:hidden" />
            
            <DropdownMenuItem 
                class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 dark:text-gray-200 cursor-pointer outline-none focus:bg-gray-100 dark:focus:bg-gray-700 rounded-md transition-colors"
                @select="router.get(route('admin.settings.index'))"
            >
                <component :is="Icons.UserIcon" class="w-4 h-4 text-gray-400" />
                <span>Profil Saya</span>
            </DropdownMenuItem>
            
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1" />
            
            <DropdownMenuItem 
                class="flex items-center gap-2 px-3 py-2 text-sm text-red-600 dark:text-red-400 cursor-pointer outline-none focus:bg-red-50 dark:focus:bg-red-900/30 rounded-md transition-colors"
                @select="handleLogout"
            >
                <component :is="Icons.LogOutIcon" class="w-4 h-4" />
                <span>Keluar</span>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenuRoot>
</template>
