<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Image as PhotoIcon, Calendar as CalendarIcon } from 'lucide-vue-next'
import PageBanner from '@/components/public/PageBanner.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { PaginationData } from '@/types'
import type { Gallery } from '@/types/gallery'

defineProps<{
    galleries: PaginationData<Gallery>
}>()

const getGalleryImage = (item: any): string | null => {
    if (!item) return null;
    const path = item.featured_image_url || item.featured_image_path || item.cover_image;
    if (!path) return null;
    return path.startsWith('http') ? path : `/storage/${path.replace(/^\//, '')}`;
};
</script>

<template>
    <Head title="Galeri Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner 
                pageKey="gallery"
                title="Galeri Desa" 
                subtitle="Dokumentasi foto dan kegiatan Desa Burujuljaya" 
            />
        </template>

        <div class="py-12 bg-slate-50 dark:bg-gray-900 transition-colors duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div v-if="galleries.data.length === 0" class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-slate-100 dark:border-gray-700">
                    <PhotoIcon class="w-16 h-16 text-slate-300 dark:text-gray-600 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-slate-900 dark:text-white">Belum ada album galeri</h3>
                    <p class="text-slate-500 dark:text-gray-400 mt-2">Album galeri akan segera ditambahkan.</p>
                </div>
                
                <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <Link 
                        v-for="gallery in galleries.data" 
                        :key="gallery.id"
                        :href="route('gallery.show', gallery.slug)"
                        class="group bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-slate-100 dark:border-gray-700 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full"
                    >
                        <div class="aspect-[4/3] relative overflow-hidden bg-slate-100 dark:bg-gray-700">
                            <img 
                                v-if="getGalleryImage(gallery)" 
                                :src="getGalleryImage(gallery)!" 
                                :alt="gallery.title" 
                                loading="lazy"
                                decoding="async"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            />
                            <div v-else class="absolute inset-0 flex items-center justify-center">
                                <PhotoIcon class="w-12 h-12 text-slate-300 dark:text-gray-500" />
                            </div>
                            
                            <div v-if="gallery.is_pinned" class="absolute top-3 right-3">
                                <span class="bg-amber-500 text-white text-xs font-bold px-2 py-1 rounded-md shadow-sm">
                                    PINNED
                                </span>
                            </div>
                            
                            <!-- Photo Count Badge -->
                            <div class="absolute bottom-3 right-3 bg-black/60 backdrop-blur-sm text-white text-xs font-medium px-2 py-1 rounded-md flex items-center gap-1.5 shadow-sm">
                                <PhotoIcon class="w-3.5 h-3.5" />
                                {{ gallery.photos_count || 0 }} Foto
                            </div>
                        </div>
                        
                        <div class="p-5 flex-1 flex flex-col">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2 mb-2">
                                {{ gallery.title }}
                            </h3>
                            
                            <p v-if="gallery.description" class="text-sm text-slate-600 dark:text-gray-300 line-clamp-2 mb-4 flex-1">
                                {{ gallery.description }}
                            </p>
                            
                            <div class="mt-auto pt-4 border-t border-slate-100 dark:border-gray-700 flex items-center text-xs text-slate-500 dark:text-gray-400">
                                <CalendarIcon class="w-4 h-4 mr-1.5" />
                                {{ gallery.published_at ? new Date(gallery.published_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-' }}
                            </div>
                        </div>
                    </Link>
                </div>
                
                <!-- Pagination -->
                <div v-if="galleries.links && galleries.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in galleries.links" :key="i">
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === galleries.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === galleries.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
