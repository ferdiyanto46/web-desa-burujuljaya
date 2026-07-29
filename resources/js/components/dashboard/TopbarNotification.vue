<script setup lang="ts">
import { DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuLabel, DropdownMenuSeparator } from 'reka-ui';
import { Icons } from '@/constants/icons';

// In a real app, this would use notificationStore
const unreadCount = 3;
</script>

<template>
    <DropdownMenuRoot>
        <DropdownMenuTrigger class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 transition-colors" aria-label="Notifikasi">
            <component :is="Icons.BellIcon" class="w-5 h-5" />
            <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
        </DropdownMenuTrigger>
        
        <DropdownMenuContent 
            class="z-50 min-w-[280px] sm:min-w-[320px] bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-1 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2"
            align="end"
            :sideOffset="8"
        >
            <DropdownMenuLabel class="px-3 py-2 text-sm font-semibold text-gray-900 dark:text-white">
                Notifikasi
            </DropdownMenuLabel>
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1" />
            
            <div class="max-h-80 overflow-y-auto">
                <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada notifikasi baru.
                </div>
            </div>
            
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1" />
            <DropdownMenuItem class="px-3 py-2 text-xs font-medium text-center text-blue-600 dark:text-blue-400 cursor-pointer outline-none focus:bg-gray-100 dark:focus:bg-gray-700 rounded-md">
                Tandai semua sudah dibaca
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenuRoot>
</template>
