<script setup lang="ts">
import { useForm, router } from '@inertiajs/vue3'
import { Edit as PencilIcon, Trash as TrashIcon, Move as ArrowsUpDownIcon, X as XMarkIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import type { Gallery } from '@/types/gallery'
import type { GalleryPhoto } from '@/types/gallery-photo'

const props = defineProps<{
    gallery: Gallery
}>()

const photos = ref<GalleryPhoto[]>([...(props.gallery.relationships?.photos || [])])

// Drag and drop sorting state
const draggedItem = ref<number | null>(null)
const draggedOverItem = ref<number | null>(null)

const onDragStart = (index: number) => {
    draggedItem.value = index
}

const onDragOver = (e: DragEvent, index: number) => {
    e.preventDefault()
    draggedOverItem.value = index
}

const onDrop = (index: number) => {
    if (draggedItem.value === null) {
        return
    }
    
    const draggedPhoto = photos.value[draggedItem.value]
    photos.value.splice(draggedItem.value, 1)
    photos.value.splice(index, 0, draggedPhoto)
    
    draggedItem.value = null
    draggedOverItem.value = null
    
    saveOrder()
}

const saveOrder = () => {
    const ulids = photos.value.map(p => p.ulid)
    router.put(route('admin.galleries.photos.reorder', props.gallery.ulid), {
        photos: ulids
    }, { preserveScroll: true })
}

const deletePhoto = (photo: GalleryPhoto, index: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        router.delete(route('admin.galleries.photos.destroy', [props.gallery.ulid, photo.ulid]), {
            preserveScroll: true,
            onSuccess: () => {
                photos.value.splice(index, 1)
            }
        })
    }
}

// Edit Modal state
const editingPhoto = ref<GalleryPhoto | null>(null)
const editForm = useForm({
    caption: '',
    alt_text: '',
    photographer: ''
})

const editPhoto = (photo: GalleryPhoto) => {
    editingPhoto.value = photo
    editForm.caption = photo.caption || ''
    editForm.alt_text = photo.alt_text || ''
    editForm.photographer = photo.photographer || ''
}

const saveEdit = () => {
    if (!editingPhoto.value) {
        return
    }
    
    editForm.put(route('admin.galleries.photos.update', [props.gallery.ulid, editingPhoto.value.ulid]), {
        preserveScroll: true,
        onSuccess: () => {
            // Update local state
            const index = photos.value.findIndex(p => p.ulid === editingPhoto.value!.ulid)

            if (index !== -1) {
                photos.value[index] = {
                    ...photos.value[index],
                    caption: editForm.caption,
                    alt_text: editForm.alt_text,
                    photographer: editForm.photographer
                }
            }

            editingPhoto.value = null
        }
    })
}
</script>

<template>
    <div class="space-y-6">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Kelola Foto ({{ photos.length }})</h3>
        
        <div v-if="photos.length === 0" class="text-center py-12 bg-gray-50 dark:bg-gray-800/40 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
            <p class="text-gray-500 dark:text-gray-400 text-sm">Belum ada foto dalam galeri ini.</p>
        </div>
        
        <div v-else class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div 
                v-for="(photo, index) in photos" 
                :key="photo.id"
                class="relative group aspect-square rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden bg-gray-100 dark:bg-gray-800 transition-transform duration-200"
                :class="{ 
                    'opacity-50 scale-95': draggedItem === index,
                    'border-emerald-500 border-2': draggedOverItem === index 
                }"
                draggable="true"
                @dragstart="onDragStart(index)"
                @dragover="onDragOver($event, index)"
                @drop="onDrop(index)"
                @dragend="draggedItem = null; draggedOverItem = null"
            >
                <img :src="photo.image_url" :alt="photo.alt_text || photo.caption || 'Gallery photo'" class="w-full h-full object-cover" />
                
                <!-- Overlay actions -->
                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3">
                    <div class="flex justify-between items-start">
                        <div class="w-8 h-8 rounded-full bg-white/20 text-white flex items-center justify-center cursor-move" title="Tahan dan geser untuk mengurutkan">
                            <ArrowsUpDownIcon class="w-4 h-4" />
                        </div>
                        
                        <div class="flex gap-2">
                            <button 
                                type="button" 
                                @click="editPhoto(photo)"
                                class="w-8 h-8 rounded-full bg-emerald-600 text-white flex items-center justify-center hover:bg-emerald-700 transition-colors shadow-sm"
                                title="Edit Info"
                            >
                                <PencilIcon class="w-4 h-4" />
                            </button>
                            
                            <button 
                                type="button" 
                                @click="deletePhoto(photo, index)"
                                class="w-8 h-8 rounded-full bg-red-600 text-white flex items-center justify-center hover:bg-red-700 transition-colors shadow-sm"
                                title="Hapus Foto"
                            >
                                <TrashIcon class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    
                    <div v-if="photo.caption" class="text-white text-xs truncate drop-shadow-md">
                        {{ photo.caption }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <Teleport to="body">
            <div v-if="editingPhoto" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm">
                <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 w-full max-w-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/40">
                        <h3 class="text-base font-bold text-gray-900 dark:text-white">Edit Info Foto</h3>
                        <button @click="editingPhoto = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                            <XMarkIcon class="w-5 h-5" />
                        </button>
                    </div>
                    
                    <form @submit.prevent="saveEdit" class="p-6 space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Caption / Judul Foto</label>
                            <input v-model="editForm.caption" type="text" class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alt Text (SEO)</label>
                            <input v-model="editForm.alt_text" type="text" class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Fotografer / Sumber</label>
                            <input v-model="editForm.photographer" type="text" class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        </div>
                        
                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="editingPhoto = null" class="px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">Batal</button>
                            <button type="submit" :disabled="editForm.processing" class="px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors disabled:opacity-50">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>
    </div>
</template>
