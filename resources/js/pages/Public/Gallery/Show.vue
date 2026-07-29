<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { DialogRoot, DialogPortal, DialogOverlay, DialogContent, DialogClose } from 'reka-ui';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

const props = defineProps<{
    album: any;
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};



// Lightbox state
const isLightboxOpen = ref(false);
const activePhotoIndex = ref(0);
const activePhoto = ref<any>(null);

const openLightbox = (index: number) => {
    activePhotoIndex.value = index;
    activePhoto.value = props.album.photos[index];
    isLightboxOpen.value = true;
};

const nextPhoto = () => {
    if (props.album.photos && activePhotoIndex.value < props.album.photos.length - 1) {
        activePhotoIndex.value++;
        activePhoto.value = props.album.photos[activePhotoIndex.value];
    }
};

const prevPhoto = () => {
    if (activePhotoIndex.value > 0) {
        activePhotoIndex.value--;
        activePhoto.value = props.album.photos[activePhotoIndex.value];
    }
};
</script>

<template>
    <Head :title="album.title" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Galeri', route: 'public.gallery.index' }, { name: album.title }]" />
        </template>
        
        <div class="py-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-12">
                <!-- Album Header -->
                <div class="p-8 md:p-12 border-b border-gray-100 dark:border-gray-700 text-center">
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4">
                        {{ album.title }}
                    </h1>
                    <div class="flex items-center justify-center gap-6 text-sm text-gray-500 dark:text-gray-400">
                        <span class="flex items-center">
                            <component :is="Icons.CalendarIcon" class="w-4 h-4 mr-1.5" />
                            {{ formatDate(album.created_at) }}
                        </span>
                        <span class="flex items-center">
                            <component :is="Icons.ImageIcon" class="w-4 h-4 mr-1.5" />
                            {{ album.photos?.length || 0 }} Foto
                        </span>
                    </div>
                    <p v-if="album.description" class="mt-6 text-gray-600 dark:text-gray-300 max-w-3xl mx-auto text-lg">
                        {{ album.description }}
                    </p>
                </div>
                
                <!-- Share Bar -->
                <div class="bg-gray-50 dark:bg-gray-900/50 px-8 py-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bagikan album ini:</span>
                    <ShareButtons :title="album.title" />
                </div>
            </div>

            <!-- Photos Grid -->
            <div v-if="album.photos && album.photos.length > 0">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    <div 
                        v-for="(photo, index) in album.photos" 
                        :key="photo.id" 
                        class="aspect-square rounded-2xl overflow-hidden bg-gray-200 dark:bg-gray-700 relative group cursor-pointer shadow-sm"
                        @click="openLightbox(index)"
                    >
                        <img :src="photo.image_url" :alt="photo.caption || album.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                        
                        <!-- Hover Overlay -->
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                            <component :is="Icons.EyeIcon" class="w-10 h-10 text-white/80" />
                        </div>
                        
                        <div v-if="photo.caption" class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-4 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <p class="text-white text-xs line-clamp-2">{{ photo.caption }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Album Kosong" 
                message="Belum ada foto di dalam album ini." 
                :icon="Icons.ImageIcon" 
                class="bg-transparent shadow-none border-none"
            />
        </div>

        <!-- Lightbox via Reka UI Dialog -->
        <DialogRoot v-model:open="isLightboxOpen">
            <DialogPortal>
                <DialogOverlay class="fixed inset-0 bg-black/95 z-[100] backdrop-blur-sm" />
                <DialogContent class="fixed inset-0 z-[100] flex flex-col items-center justify-center p-4 sm:p-8 outline-none">
                    
                    <div class="absolute top-4 right-4 sm:top-6 sm:right-6 flex gap-4">
                        <DialogClose class="w-12 h-12 rounded-full bg-white/10 hover:bg-white/20 text-white flex items-center justify-center transition-colors">
                            <component :is="Icons.XIcon" class="w-6 h-6" />
                        </DialogClose>
                    </div>

                    <div class="w-full max-w-5xl h-full flex flex-col items-center justify-center relative select-none">
                        <!-- Navigation Prev -->
                        <button 
                            v-if="activePhotoIndex > 0"
                            @click="prevPhoto"
                            class="absolute left-0 sm:-left-12 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors border border-white/10 z-10"
                        >
                            <component :is="Icons.ChevronLeftIcon" class="w-8 h-8" />
                        </button>
                        
                        <!-- Navigation Next -->
                        <button 
                            v-if="activePhotoIndex < album.photos.length - 1"
                            @click="nextPhoto"
                            class="absolute right-0 sm:-right-12 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors border border-white/10 z-10"
                        >
                            <component :is="Icons.ChevronRightIcon" class="w-8 h-8" />
                        </button>

                        <div class="relative w-full h-[75vh] flex items-center justify-center" v-if="activePhoto">
                            <img 
                                :src="activePhoto.image_url" 
                                :alt="activePhoto.caption || album.title" 
                                class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" 
                            />
                        </div>
                        
                        <div class="mt-6 text-center" v-if="activePhoto">
                            <p class="text-white text-lg max-w-3xl mx-auto">{{ activePhoto.caption }}</p>
                            <p class="text-white/50 text-sm mt-2">{{ activePhotoIndex + 1 }} dari {{ album.photos.length }}</p>
                        </div>
                    </div>

                </DialogContent>
            </DialogPortal>
        </DialogRoot>

    </PublicLayout>
</template>