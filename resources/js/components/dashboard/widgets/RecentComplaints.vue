<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icons } from '@/constants/icons';

defineProps<{
    complaints: Array<{
        id: number;
        tracking_code: string;
        title: string;
        status: string;
        created_at: string;
    }>
}>();

const formatTimeAgo = (dateString: string) => {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);
    
    if (diffInSeconds < 60) {
return 'Baru saja';
}
    
    const diffInMinutes = Math.floor(diffInSeconds / 60);

    if (diffInMinutes < 60) {
return `${diffInMinutes} menit lalu`;
}
    
    const diffInHours = Math.floor(diffInMinutes / 60);

    if (diffInHours < 24) {
return `${diffInHours} jam lalu`;
}
    
    const diffInDays = Math.floor(diffInHours / 24);

    if (diffInDays < 7) {
return `${diffInDays} hari lalu`;
}
    
    return new Intl.DateTimeFormat('id-ID', { day: 'numeric', month: 'short' }).format(date);
};

const getStatusColor = (status: string) => {
    switch(status) {
        case 'pending': return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
        case 'processing': return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
        case 'resolved': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
        case 'rejected': return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        default: return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
    }
};

const getStatusLabel = (status: string) => {
    switch(status) {
        case 'pending': return 'Menunggu';
        case 'processing': return 'Diproses';
        case 'resolved': return 'Selesai';
        case 'rejected': return 'Ditolak';
        default: return status;
    }
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col h-full">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <component :is="Icons.AlertCircleIcon" class="w-5 h-5 text-red-500" />
                Pengaduan Terbaru
            </h3>
            <Link :href="route('admin.complaints.index')" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                Lihat Semua
            </Link>
        </div>
        
        <div class="p-0 overflow-y-auto max-h-[400px]">
            <ul v-if="complaints.length > 0" class="divide-y divide-gray-100 dark:divide-gray-700">
                <li v-for="complaint in complaints" :key="complaint.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ complaint.title }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-2">
                                <span class="font-mono">{{ complaint.tracking_code }}</span>
                                <span>&bull;</span>
                                <span>{{ formatTimeAgo(complaint.created_at) }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span :class="['inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-black/5 dark:ring-white/10', getStatusColor(complaint.status)]">
                                {{ getStatusLabel(complaint.status) }}
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-else class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Belum ada pengaduan.
            </div>
        </div>
    </div>
</template>
