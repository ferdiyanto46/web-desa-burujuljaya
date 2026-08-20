<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { 
    ArrowLeft as ArrowLeftIcon, 
    Calendar as CalendarIcon, 
    ChevronLeft as ChevronLeftIcon, 
    ChevronRight as ChevronRightIcon, 
    Image as PhotoIcon, 
    X as XMarkIcon 
} from 'lucide-vue-next'
import { computed, onMounted, onUnmounted, ref } from 'vue'
import Breadcrumb from '@/components/public/Breadcrumb.vue'
import PageBanner from '@/components/public/PageBanner.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { Gallery } from '@/types/gallery'

const props = defineProps<{
    gallery: { data: Gallery } | Gallery
}>()

const resolvedGallery = computed<Gallery>(() => {
    return ((props.gallery as any)?.data || props.gallery) as Gallery
})

// Lightbox state
const lightboxOpen = ref(false)
const currentPhotoIndex = ref(0)
const photos = computed(() => {
    const gal = resolvedGallery.value
    return gal?.relationships?.photos || (gal as any)?.photos || []
})

const openLightbox = (index: number) => {
    currentPhotoIndex.value = index
    lightboxOpen.value = true
    document.body.style.overflow = 'hidden'
}

const closeLightbox = () => {
    lightboxOpen.value = false
    document.body.style.overflow = ''
}

const nextPhoto = () => {
    if (photos.value.length === 0) return
    if (currentPhotoIndex.value < photos.value.length - 1) {
        currentPhotoIndex.value++
    } else {
        currentPhotoIndex.value = 0
    }
}

const prevPhoto = () => {
    if (photos.value.length === 0) return
    if (currentPhotoIndex.value > 0) {
        currentPhotoIndex.value--
    } else {
        currentPhotoIndex.value = photos.value.length - 1
    }
}

const handleKeydown = (e: KeyboardEvent) => {
    if (!lightboxOpen.value) return
    if (e.key === 'Escape') closeLightbox()
    if (e.key === 'ArrowRight') nextPhoto()
    if (e.key === 'ArrowLeft') prevPhoto()
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
    document.body.style.overflow = ''
})

const getPhotoUrl = (photo: any): string => {
    if (!photo) return ''
    const path = photo.image_url || photo.file_path
    if (!path) return ''
    return path.startsWith('http') ? path : `/storage/${path.replace(/^\//, '')}`
}
</script>

<template>
    <Head :title="resolvedGallery?.title || 'Detail Galeri'" />

    <PublicLayout>
        <!-- Page Banner -->
        <template #banner>
            <PageBanner 
                pageKey="gallery"
                :title="resolvedGallery?.title || 'Detail Galeri'" 
                :subtitle="`Dokumentasi Kegiatan Desa Burujuljaya • ${photos.length} Foto`" 
                :backgroundImage="resolvedGallery?.featured_image_url || undefined"
            />
        </template>

        <!-- Breadcrumb -->
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Galeri', route: 'gallery.index' }, { name: resolvedGallery?.title || 'Detail Galeri' }]" />
        </template>

        <div class="py-4 space-y-8">
            <!-- Header navigation & Meta -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-6 border-b border-gray-200 dark:border-gray-800">
                <Link 
                    :href="route('gallery.index')" 
                    class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors w-max"
                >
                    <ArrowLeftIcon class="w-4 h-4" />
                    <span>Kembali ke Semua Galeri</span>
                </Link>

                <div class="flex items-center gap-4 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center gap-1.5">
                        <CalendarIcon class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ resolvedGallery?.published_at ? new Date(resolvedGallery.published_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <PhotoIcon class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                        <span>{{ photos.length }} Foto</span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div 
                v-if="resolvedGallery?.description" 
                class="bg-white dark:bg-gray-800/80 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 text-base leading-relaxed shadow-sm"
            >
                <p>{{ resolvedGallery.description }}</p>
            </div>

            <!-- Photos Grid -->
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                    <PhotoIcon class="w-5 h-5 text-emerald-600 dark:text-emerald-400" />
                    <span>Album Foto ({{ photos.length }})</span>
                </h2>

                <div v-if="photos.length === 0" class="text-center py-20 bg-white dark:bg-gray-800 rounded-2xl border border-dashed border-gray-200 dark:border-gray-700 shadow-sm">
                    <PhotoIcon class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" />
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum ada foto</h3>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Album galeri ini belum memiliki foto dokumentasi.</p>
                </div>
                
                <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    <button 
                        v-for="(photo, index) in photos" 
                        :key="photo.id || index"
                        @click="openLightbox(index)"
                        type="button"
                        class="group relative aspect-square rounded-2xl overflow-hidden bg-gray-100 dark:bg-gray-800 focus:outline-none focus:ring-4 focus:ring-emerald-500/30 transition-all shadow-sm hover:shadow-lg border border-gray-200 dark:border-gray-700 cursor-pointer transform hover:-translate-y-1 duration-300"
                    >
                        <img 
                            :src="getPhotoUrl(photo)" 
                            :alt="photo.alt_text || photo.caption || 'Foto Galeri'" 
                            loading="lazy"
                            decoding="async"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        />
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4 text-left">
                            <p v-if="photo.caption" class="text-white text-sm font-semibold line-clamp-2 drop-shadow-md">
                                {{ photo.caption }}
                            </p>
                            <p v-if="photo.photographer" class="text-gray-300 text-xs mt-1 drop-shadow-md">
                                &copy; {{ photo.photographer }}
                            </p>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </PublicLayout>

    <!-- Lightbox Modal -->
    <Teleport to="body">
        <div 
            v-if="lightboxOpen" 
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/95 backdrop-blur-md animate-in fade-in duration-200 select-none"
            @click.self="closeLightbox"
        >
            <!-- Close Button -->
            <button 
                @click="closeLightbox" 
                class="absolute top-4 right-4 p-2.5 text-white/70 hover:text-white rounded-full bg-white/10 hover:bg-white/20 transition-colors z-50 focus:outline-none"
                title="Tutup (Esc)"
            >
                <XMarkIcon class="w-6 h-6" />
            </button>
            
            <!-- Prev/Next Controls -->
            <button 
                @click.stop="prevPhoto" 
                class="absolute left-4 p-3 text-white/70 hover:text-white rounded-full bg-white/10 hover:bg-white/20 transition-colors hidden sm:block z-50 focus:outline-none"
                title="Sebelumnya (Panah Kiri)"
            >
                <ChevronLeftIcon class="w-7 h-7" />
            </button>
            
            <button 
                @click.stop="nextPhoto" 
                class="absolute right-4 p-3 text-white/70 hover:text-white rounded-full bg-white/10 hover:bg-white/20 transition-colors hidden sm:block z-50 focus:outline-none"
                title="Selanjutnya (Panah Kanan)"
            >
                <ChevronRightIcon class="w-7 h-7" />
            </button>

            <!-- Active Photo Content -->
            <div class="relative max-w-5xl max-h-[90vh] p-4 flex flex-col items-center justify-center">
                <img 
                    :src="getPhotoUrl(photos[currentPhotoIndex])" 
                    :alt="photos[currentPhotoIndex]?.caption || 'Foto Galeri'" 
                    class="max-w-full max-h-[75vh] object-contain rounded-xl shadow-2xl"
                />
                
                <div class="mt-4 text-center text-white space-y-1">
                    <p v-if="photos[currentPhotoIndex]?.caption" class="text-base font-medium">
                        {{ photos[currentPhotoIndex]?.caption }}
                    </p>
                    <p class="text-xs text-gray-400">
                        Foto {{ currentPhotoIndex + 1 }} dari {{ photos.length }}
                        <span v-if="photos[currentPhotoIndex]?.photographer"> &bull; &copy; {{ photos[currentPhotoIndex]?.photographer }}</span>
                    </p>
                </div>
            </div>
        </div>
    </Teleport>
</template>
