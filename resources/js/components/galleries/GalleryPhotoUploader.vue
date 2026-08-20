<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import { UploadCloud as CloudArrowUpIcon, X as XMarkIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import type { Gallery } from '@/types/gallery'

const props = defineProps<{
    gallery: Gallery
}>()

const fileInput = ref<HTMLInputElement | null>(null)
const previewFiles = ref<Array<{ file: File, url: string }>>([])
const isDragging = ref(false)

const form = useForm({
    photos: [] as File[]
})

const handleFileDrop = (e: DragEvent) => {
    isDragging.value = false

    if (e.dataTransfer?.files) {
        addFiles(Array.from(e.dataTransfer.files))
    }
}

const handleFileSelect = (e: Event) => {
    const input = e.target as HTMLInputElement

    if (input.files) {
        addFiles(Array.from(input.files))
        input.value = '' // Reset input
    }
}

const addFiles = (files: File[]) => {
    const imageFiles = files.filter(f => f.type.startsWith('image/'))
    
    // Create previews
    imageFiles.forEach(file => {
        previewFiles.value.push({
            file,
            url: URL.createObjectURL(file)
        })
    })
    
    form.photos = previewFiles.value.map(p => p.file)
}

const removeFile = (index: number) => {
    URL.revokeObjectURL(previewFiles.value[index].url)
    previewFiles.value.splice(index, 1)
    form.photos = previewFiles.value.map(p => p.file)
}

const clearFiles = () => {
    previewFiles.value.forEach(p => URL.revokeObjectURL(p.url))
    previewFiles.value = []
    form.photos = []
}

const uploadFiles = () => {
    if (form.photos.length === 0) {
        return
    }
    
    form.post(route('admin.galleries.photos.store', props.gallery.ulid), {
        preserveScroll: true,
        onSuccess: () => {
            clearFiles()
        }
    })
}
</script>

<template>
    <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
        <div class="p-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Unggah Foto</h3>
            
            <!-- Dropzone -->
            <div 
                class="border-2 border-dashed rounded-xl p-8 text-center transition-colors cursor-pointer"
                :class="isDragging 
                    ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' 
                    : 'border-gray-300 dark:border-gray-700 hover:border-emerald-500 dark:hover:border-emerald-400 bg-gray-50/50 dark:bg-gray-800/30'"
                @dragover.prevent="isDragging = true"
                @dragleave.prevent="isDragging = false"
                @drop.prevent="handleFileDrop"
                @click="fileInput?.click()"
            >
                <input 
                    type="file" 
                    ref="fileInput" 
                    class="hidden" 
                    multiple 
                    accept="image/*"
                    @change="handleFileSelect" 
                />
                
                <div class="flex justify-center mb-3">
                    <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                        <CloudArrowUpIcon class="w-6 h-6" />
                    </div>
                </div>
                
                <p class="text-gray-900 dark:text-white font-semibold text-sm">Klik untuk unggah atau seret file ke sini</p>
                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Maksimal 5MB per file (JPG, PNG, WEBP)</p>
            </div>
            
            <!-- Previews -->
            <div v-if="previewFiles.length > 0" class="mt-6">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Preview ({{ previewFiles.length }} foto)</h4>
                    <button type="button" @click="clearFiles" class="text-xs text-red-600 dark:text-red-400 hover:underline font-semibold">
                        Hapus Semua
                    </button>
                </div>
                
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    <div 
                        v-for="(preview, index) in previewFiles" 
                        :key="index"
                        class="relative group aspect-square rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-100 dark:bg-gray-800"
                    >
                        <img :src="preview.url" class="w-full h-full object-cover" />
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button 
                                type="button" 
                                @click="removeFile(index)"
                                class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transform hover:scale-110 transition-transform"
                            >
                                <XMarkIcon class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button
                        type="button"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors disabled:opacity-50"
                        @click="uploadFiles"
                    >
                        Mulai Unggah {{ previewFiles.length }} Foto
                    </button>
                </div>
                
                <div v-if="form.errors.photos" class="mt-2 text-sm text-red-600 dark:text-red-400">
                    {{ form.errors.photos }}
                </div>
            </div>
        </div>
        
        <div v-if="form.progress" class="bg-emerald-50 dark:bg-emerald-950/40 px-6 py-3 border-t border-emerald-100 dark:border-emerald-900/50">
            <div class="flex items-center justify-between text-sm text-emerald-700 dark:text-emerald-300 mb-1">
                <span>Mengunggah...</span>
                <span>{{ form.progress.percentage }}%</span>
            </div>
            <div class="w-full bg-emerald-200 dark:bg-emerald-900 rounded-full h-2">
                <div class="bg-emerald-600 h-2 rounded-full transition-all duration-300" :style="{ width: `${form.progress.percentage}%` }"></div>
            </div>
        </div>
    </div>
</template>
