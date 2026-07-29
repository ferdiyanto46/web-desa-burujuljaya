<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icons } from '@/constants/icons';

defineProps<{
    articles: Array<{
        id: number;
        title: string;
        status: string;
        published_at: string | null;
        user?: {
            name: string;
        };
    }>
}>();

const formatDate = (dateString: string | null) => {
    if (!dateString) {
return 'Draft';
}

    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    }).format(new Date(dateString));
};

const getStatusColor = (status: string) => {
    switch(status) {
        case 'published': return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
        case 'draft': return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
        case 'archived': return 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400';
        default: return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400';
    }
};

const getStatusLabel = (status: string) => {
    switch(status) {
        case 'published': return 'Dipublikasi';
        case 'draft': return 'Draf';
        case 'archived': return 'Diarsipkan';
        default: return status;
    }
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col h-full">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <component :is="Icons.FileTextIcon" class="w-5 h-5 text-blue-500" />
                Artikel Terbaru
            </h3>
            <Link :href="route('admin.articles.index')" class="text-sm font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400">
                Lihat Semua
            </Link>
        </div>
        
        <div class="p-0 overflow-y-auto max-h-[400px]">
            <ul v-if="articles.length > 0" class="divide-y divide-gray-100 dark:divide-gray-700">
                <li v-for="article in articles" :key="article.id" class="p-6 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                {{ article.title }}
                            </p>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate flex items-center gap-2">
                                <span class="font-medium">{{ article.user?.name || 'Sistem' }}</span>
                                <span>&bull;</span>
                                <span>{{ formatDate(article.published_at) }}</span>
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <span :class="['inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset ring-black/5 dark:ring-white/10', getStatusColor(article.status)]">
                                {{ getStatusLabel(article.status) }}
                            </span>
                        </div>
                    </div>
                </li>
            </ul>
            <div v-else class="p-6 text-center text-sm text-gray-500 dark:text-gray-400">
                Belum ada artikel.
            </div>
        </div>
    </div>
</template>
