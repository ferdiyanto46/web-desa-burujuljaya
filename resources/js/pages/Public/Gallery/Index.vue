<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    albums: {
        data: Array<any>;
        links: Array<any>;
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
    <Head title="Album Galeri" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Galeri Desa" subtitle="Kumpulan dokumentasi foto kegiatan dan potensi Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Galeri' }]" />
        </template>
        
        <div class="py-8">
            <div v-if="albums.data.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <Link 
                        v-for="album in albums.data" 
                        :key="album.id" 
                        :href="route('gallery.show', album.slug)"
                        class="group bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md hover:border-blue-200 dark:hover:border-blue-900/50 transition-all flex flex-col"
                    >
                        <!-- Cover Image Area -->
                        <div class="relative h-64 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <!-- Show first photo as cover if available -->
                            <img 
                                v-if="album.photos && album.photos.length > 0 && album.photos[0].image_url" 
                                :src="album.photos[0].image_url" 
                                :alt="album.title" 
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" 
                            />
                            <div v-else class="absolute inset-0 flex items-center justify-center">
                                <component :is="Icons.ImageIcon" class="w-12 h-12 text-gray-400" />
                            </div>
                            
                            <!-- Overlay Info -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent flex flex-col justify-end p-6">
                                <div class="flex items-center gap-2 text-white/90 text-sm mb-2">
                                    <component :is="Icons.CalendarIcon" class="w-4 h-4" />
                                    <span>{{ formatDate(album.created_at) }}</span>
                                </div>
                                <h3 class="text-xl font-bold text-white group-hover:text-blue-300 transition-colors line-clamp-2">
                                    {{ album.title }}
                                </h3>
                            </div>
                            
                            <!-- Photos Count Badge -->
                            <div class="absolute top-4 right-4 bg-black/50 backdrop-blur-md text-white text-xs font-bold px-3 py-1.5 rounded-full flex items-center">
                                <component :is="Icons.ImageIcon" class="w-3 h-3 mr-1.5" />
                                Album
                            </div>
                        </div>
                        
                        <!-- Details -->
                        <div class="p-6 flex-grow flex flex-col">
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 flex-grow">{{ album.description || 'Tidak ada deskripsi.' }}</p>
                            <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400 group-hover:underline">Lihat Album</span>
                                <div class="w-8 h-8 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    <component :is="Icons.ChevronRightIcon" class="w-4 h-4" />
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="albums.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in albums.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === albums.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === albums.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Galeri Kosong" 
                message="Belum ada album foto yang dipublikasikan saat ini." 
                :icon="Icons.ImageIcon" 
            />
        </div>
    </PublicLayout>
</template>
