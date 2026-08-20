<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { DialogRoot, DialogPortal, DialogOverlay, DialogContent, DialogClose } from 'reka-ui';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

const props = defineProps<{
    tourism: any;
}>();



// Lightbox state for gallery
const isLightboxOpen = ref(false);
const activePhotoIndex = ref(0);
const activePhoto = ref<any>(null);
const galleryImages = props.tourism.images || [];

const openLightbox = (index: number) => {
    activePhotoIndex.value = index;
    activePhoto.value = galleryImages[index];
    isLightboxOpen.value = true;
};

const nextPhoto = () => {
    if (activePhotoIndex.value < galleryImages.length - 1) {
        activePhotoIndex.value++;
        activePhoto.value = galleryImages[activePhotoIndex.value];
    }
};

const prevPhoto = () => {
    if (activePhotoIndex.value > 0) {
        activePhotoIndex.value--;
        activePhoto.value = galleryImages[activePhotoIndex.value];
    }
};
</script>

<template>
    <Head :title="tourism.name" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Wisata', route: 'public.tourism.index' }, { name: tourism.name }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-6xl mx-auto">
            <!-- Hero Header -->
            <div class="relative h-[400px] md:h-[500px] rounded-3xl overflow-hidden mb-12 shadow-lg">
                <img v-if="tourism.thumbnail_url" :src="tourism.thumbnail_url" :alt="tourism.name" class="w-full h-full object-cover" />
                <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><component :is="Icons.MapIcon" class="w-20 h-20 text-gray-400" /></div>
                
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent flex flex-col justify-end p-8 md:p-12">
                    <span v-if="tourism.category" class="inline-flex w-max items-center px-3 py-1 rounded-full text-sm font-bold bg-blue-600 text-white mb-4 shadow-sm">
                        {{ tourism.category.name }}
                    </span>
                    <h1 class="text-3xl md:text-5xl font-extrabold text-white leading-tight mb-4">
                        {{ tourism.name }}
                    </h1>
                    <div class="flex items-center text-white/90 text-sm md:text-base">
                        <component :is="Icons.MapIcon" class="w-5 h-5 mr-2 text-red-500" />
                        {{ tourism.location }}
                    </div>
                </div>
            </div>
            
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Main Content -->
                <div class="lg:w-2/3">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-8 md:p-12 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Informasi Destinasi</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            <div v-html="tourism.content || `<p>${tourism.description}</p>`"></div>
                        </div>
                    </div>
                    
                    <!-- Gallery -->
                    <div v-if="galleryImages.length > 0" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-8 md:p-12">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Galeri Wisata</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div 
                                v-for="(image, index) in galleryImages" 
                                :key="index"
                                class="aspect-square rounded-2xl overflow-hidden cursor-pointer group shadow-sm"
                                @click="openLightbox(index)"
                            >
                                <img :src="image.image_url" :alt="`${tourism.name} - ${index + 1}`" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:w-1/3 space-y-8">
                    <!-- Info Card -->
                    <div class="bg-blue-50 dark:bg-gray-800 rounded-3xl shadow-sm border border-blue-100 dark:border-gray-700 p-8">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b border-blue-200 dark:border-gray-700 pb-4">Info Praktis</h3>
                        
                        <div class="space-y-6">
                            <!-- Admission Fee -->
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                    <component :is="Icons.CheckCircleIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Tiket Masuk</p>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ tourism.admission_fee || 'Gratis' }}</p>
                                </div>
                            </div>
                            
                            <!-- Operating Hours -->
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                    <component :is="Icons.ClockIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Jam Operasional</p>
                                    <p class="font-bold text-gray-900 dark:text-white whitespace-pre-line">{{ tourism.operating_hours || 'Setiap Hari' }}</p>
                                </div>
                            </div>
                            
                            <!-- Contact -->
                            <div class="flex items-start gap-4" v-if="tourism.contact_number">
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 flex-shrink-0">
                                    <component :is="Icons.PhoneIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Kontak</p>
                                    <a :href="`tel:${tourism.contact_number}`" class="font-bold text-blue-600 dark:text-blue-400 hover:underline">{{ tourism.contact_number }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Share -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                        <h3 class="text-sm font-bold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Bagikan Destinasi Ini</h3>
                        <div class="flex justify-center">
                            <ShareButtons :title="tourism.name" />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox -->
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
                        <button v-if="activePhotoIndex > 0" @click="prevPhoto" class="absolute left-0 sm:-left-12 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors border border-white/10 z-10"><component :is="Icons.ChevronLeftIcon" class="w-8 h-8" /></button>
                        <button v-if="activePhotoIndex < galleryImages.length - 1" @click="nextPhoto" class="absolute right-0 sm:-right-12 w-12 h-12 rounded-full bg-black/50 hover:bg-black/80 text-white flex items-center justify-center transition-colors border border-white/10 z-10"><component :is="Icons.ChevronRightIcon" class="w-8 h-8" /></button>

                        <div class="relative w-full h-[75vh] flex items-center justify-center" v-if="activePhoto">
                            <img :src="activePhoto.image_url" :alt="tourism.name" class="max-w-full max-h-full object-contain rounded-lg shadow-2xl" />
                        </div>
                        
                        <div class="mt-6 text-center" v-if="activePhoto">
                            <p class="text-white/50 text-sm mt-2">{{ activePhotoIndex + 1 }} dari {{ galleryImages.length }}</p>
                        </div>
                    </div>
                </DialogContent>
            </DialogPortal>
        </DialogRoot>

    </PublicLayout>
</template>
