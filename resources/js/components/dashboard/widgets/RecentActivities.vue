<script setup lang="ts">
import { Icons } from '@/constants/icons';

defineProps<{
    activities: Array<{
        id: number;
        description: string;
        created_at: string;
        user?: {
            name: string;
        };
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
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col h-full">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <component :is="Icons.HistoryIcon" class="w-5 h-5 text-gray-500" />
                Aktivitas Sistem
            </h3>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[400px]">
            <div v-if="activities.length > 0" class="flow-root">
                <ul role="list" class="-mb-8">
                    <li v-for="(activity, activityIdx) in activities" :key="activity.id">
                        <div class="relative pb-8">
                            <span v-if="activityIdx !== activities.length - 1" class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true" />
                            <div class="relative flex space-x-3">
                                <div>
                                    <span class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center ring-8 ring-white dark:ring-gray-800">
                                        <component :is="Icons.UserIcon" class="h-4 w-4 text-gray-500 dark:text-gray-400" aria-hidden="true" />
                                    </span>
                                </div>
                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                    <div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            <span class="font-medium text-gray-900 dark:text-white">{{ activity.user?.name || 'Sistem' }}</span> 
                                            {{ activity.description }}
                                        </p>
                                    </div>
                                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                                        <time :datetime="activity.created_at">{{ formatTimeAgo(activity.created_at) }}</time>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <div v-else class="text-center text-sm text-gray-500 dark:text-gray-400">
                Belum ada aktivitas terekam.
            </div>
        </div>
    </div>
</template>
