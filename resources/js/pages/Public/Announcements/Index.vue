<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    announcements: {
        data: any[];
        links: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};
</script>

<template>
    <Head title="Pengumuman" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Pengumuman Desa" subtitle="Informasi penting dan pemberitahuan resmi dari Pemerintah Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pengumuman' }]" />
        </template>
        
        <div class="py-8">
            <div v-if="announcements.data.length > 0" class="max-w-4xl mx-auto">
                <div class="space-y-6 mb-12">
                    <Link 
                        v-for="announcement in announcements.data" 
                        :key="announcement.id" 
                        :href="route('announcements.show', announcement.slug)" 
                        class="block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 md:p-8 hover:shadow-md hover:border-blue-200 dark:hover:border-blue-900/50 transition-all group"
                        :class="{'border-l-4 border-l-blue-500': announcement.is_pinned}"
                    >
                        <div class="flex items-start gap-4">
                            <div class="hidden sm:flex flex-shrink-0 w-12 h-12 rounded-full items-center justify-center shadow-sm" :class="announcement.is_pinned ? 'bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400'">
                                <component :is="Icons.MegaphoneIcon" class="w-6 h-6" />
                            </div>
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span v-if="announcement.is_pinned" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                        Disematkan
                                    </span>
                                    <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                        <component :is="Icons.CalendarIcon" class="w-4 h-4 mr-1" />
                                        {{ formatDate(announcement.published_at) }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                    {{ announcement.title }}
                                </h3>
                                <p class="text-gray-600 dark:text-gray-300 line-clamp-2">{{ announcement.excerpt }}</p>
                                
                                <div class="mt-4 flex justify-end">
                                    <span class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center">
                                        Baca Detail <component :is="Icons.ChevronRightIcon" class="w-4 h-4 ml-1 group-hover:translate-x-1 transition-transform" />
                                    </span>
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="announcements.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in announcements.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === announcements.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === announcements.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Tidak Ada Pengumuman" 
                message="Belum ada pengumuman resmi yang diterbitkan saat ini." 
                :icon="Icons.MegaphoneIcon" 
            />
        </div>
    </PublicLayout>
</template>
