<script setup lang="ts">
import { usePage, router } from '@inertiajs/vue3';
import { DropdownMenuRoot, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator } from 'reka-ui';
import { computed } from 'vue';
import { Icons } from '@/constants/icons';
import { route } from '@/lib/route';

interface ComplaintNotification {
    id: string;
    title: string;
    reporter_name: string;
    created_at: string;
}

const page = usePage();

const unreadCount = computed(() => {
    return (page.props.notifications as any)?.unread_complaints_count || 0;
});

const recentComplaints = computed<ComplaintNotification[]>(() => {
    return (page.props.notifications as any)?.recent_complaints || [];
});

const goToComplaints = () => {
    router.get(route('admin.complaints.index'));
};

const goToComplaintDetail = (id: string) => {
    router.get(route('admin.complaints.show', id));
};
</script>

<template>
    <DropdownMenuRoot>
        <DropdownMenuTrigger class="relative p-2 rounded-full text-gray-500 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-800 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 transition-colors" aria-label="Notifikasi Pengaduan">
            <component :is="Icons.BellIcon" class="w-5 h-5" />
            <span v-if="unreadCount > 0" class="absolute top-1.5 right-1.5 flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
            </span>
        </DropdownMenuTrigger>
        
        <DropdownMenuContent 
            class="z-50 min-w-[280px] sm:min-w-[340px] bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-1 data-[state=open]:animate-in data-[state=closed]:animate-out data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0 data-[state=closed]:zoom-out-95 data-[state=open]:zoom-in-95 data-[side=bottom]:slide-in-from-top-2"
            align="end"
            :sideOffset="8"
        >
            <div class="px-3 py-2 flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-900 dark:text-white">
                    Pengaduan Masuk
                </span>
                <span v-if="unreadCount > 0" class="px-2 py-0.5 text-xs font-bold bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-400 rounded-full">
                    {{ unreadCount }} Baru
                </span>
            </div>
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1" />
            
            <div class="max-h-80 overflow-y-auto">
                <template v-if="recentComplaints.length > 0">
                    <DropdownMenuItem 
                        v-for="item in recentComplaints" 
                        :key="item.id"
                        class="flex flex-col items-start gap-1 px-3 py-2.5 cursor-pointer outline-none focus:bg-gray-100 dark:focus:bg-gray-700 rounded-md transition-colors border-b border-gray-100 dark:border-gray-700/50 last:border-0"
                        @select="goToComplaintDetail(item.id)"
                    >
                        <div class="flex items-center justify-between w-full">
                            <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 truncate max-w-[180px]">
                                {{ item.reporter_name }}
                            </span>
                            <span class="text-[10px] text-gray-400">
                                {{ item.created_at }}
                            </span>
                        </div>
                        <span class="text-xs font-medium text-gray-800 dark:text-gray-200 line-clamp-1">
                            {{ item.title }}
                        </span>
                    </DropdownMenuItem>
                </template>
                <div v-else class="px-4 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada pengaduan baru.
                </div>
            </div>
            
            <DropdownMenuSeparator class="h-px bg-gray-200 dark:bg-gray-700 my-1" />
            <DropdownMenuItem 
                class="px-3 py-2 text-xs font-medium text-center justify-center text-blue-600 dark:text-blue-400 cursor-pointer outline-none focus:bg-gray-100 dark:focus:bg-gray-700 rounded-md"
                @select="goToComplaints"
            >
                Lihat Semua Pengaduan
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenuRoot>
</template>
