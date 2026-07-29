<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    announcement: any;
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};


</script>

<template>
    <Head :title="announcement.title" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pengumuman', route: 'public.announcements.index' }, { name: announcement.title }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <!-- Header -->
                <div class="bg-gray-50 dark:bg-gray-900/50 px-8 md:px-12 py-8 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-4">
                        <span v-if="announcement.is_pinned" class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                            Disematkan
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                            <component :is="Icons.CalendarIcon" class="w-4 h-4 mr-1" />
                            Diterbitkan {{ formatDate(announcement.published_at) }}
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                        {{ announcement.title }}
                    </h1>
                </div>

                <!-- Content -->
                <div class="px-8 md:px-12 py-10">
                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mb-12">
                        <div v-html="announcement.content"></div>
                    </div>

                    <!-- Author & Share -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 border-t border-gray-100 dark:border-gray-700 gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                <component :is="Icons.UserIcon" class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ announcement.author?.name || 'Pemerintah Desa Burujuljaya' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Penerbit Pengumuman</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bagikan:</span>
                            <ShareButtons :title="announcement.title" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
