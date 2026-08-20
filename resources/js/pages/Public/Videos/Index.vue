<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    videos: {
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
    <Head title="Galeri Video" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Galeri Video" subtitle="Dokumentasi visual kegiatan dan potensi Desa Burujuljaya dalam bentuk video" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Video' }]" />
        </template>
        
        <div class="py-8">
            <div v-if="videos.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div 
                        v-for="video in videos.data" 
                        :key="video.id" 
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden group hover:shadow-md transition-shadow flex flex-col"
                    >
                        <div class="aspect-video w-full bg-gray-900 relative">
                            <!-- We use iframe with loading="lazy" as per user requirements -->
                            <iframe 
                                :src="`https://www.youtube.com/embed/${video.youtube_id}`" 
                                :title="video.title"
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen
                                loading="lazy"
                                class="absolute inset-0 w-full h-full"
                            ></iframe>
                        </div>
                        
                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">{{ video.title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3 mb-4 flex-grow">{{ video.description || 'Tidak ada deskripsi.' }}</p>
                            
                            <div class="mt-auto flex justify-between items-center pt-4 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center">
                                    <component :is="Icons.CalendarIcon" class="w-4 h-4 mr-1.5" />
                                    {{ formatDate(video.created_at) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="videos.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in videos.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === videos.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === videos.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Galeri Video Kosong" 
                message="Belum ada video yang dipublikasikan saat ini." 
                :icon="Icons.VideoIcon" 
            />
        </div>
    </PublicLayout>
</template>
