<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3'
import { Image as PhotoIcon } from 'lucide-vue-next'
import FormInput from '@/components/forms/FormInput.vue'
import FormSelect from '@/components/forms/FormSelect.vue'
import FormSwitch from '@/components/forms/FormSwitch.vue'
import FormTextarea from '@/components/forms/FormTextarea.vue'
import ImageUploader from '@/components/ui/upload/ImageUploader.vue'
import type { Gallery } from '@/types/gallery'

const props = defineProps<{
    gallery?: Gallery
}>()

const isEditing = !!props.gallery

const form = useForm({
    title: props.gallery?.title ?? '',
    description: props.gallery?.description ?? '',
    status: props.gallery?.status ?? 'draft',
    is_pinned: props.gallery?.is_pinned ?? false,
    featured_image: null as File | null,
    _method: isEditing ? 'PUT' : 'POST'
})

const submit = () => {
    if (isEditing) {
        form.post(route('admin.galleries.update', props.gallery!.ulid), {
            preserveScroll: true
        })
    } else {
        form.post(route('admin.galleries.store'), {
            preserveScroll: true
        })
    }
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Title -->
                <FormInput
                    v-model="form.title"
                    label="Judul Galeri"
                    placeholder="Contoh: Kegiatan Peringatan HUT RI ke-79"
                    :error="form.errors.title"
                    required
                />

                <!-- Description -->
                <FormTextarea
                    v-model="form.description"
                    label="Deskripsi"
                    placeholder="Tuliskan deskripsi singkat tentang galeri ini..."
                    :error="form.errors.description"
                    :rows="4"
                />

                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Status -->
                    <FormSelect
                        v-model="form.status"
                        label="Status"
                        :error="form.errors.status"
                        :options="[
                            { value: 'draft', label: 'Draft' },
                            { value: 'published', label: 'Published' }
                        ]"
                        class="flex-1"
                    />

                    <!-- Pinned -->
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pin ke Atas</label>
                        <div class="pt-2">
                            <FormSwitch
                                v-model="form.is_pinned"
                                label="Tampilkan di urutan teratas"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800/60 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                        <PhotoIcon class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                        Sampul Galeri
                    </h3>
                    
                    <ImageUploader
                        v-model="form.featured_image"
                        :image-url="gallery?.featured_image_url"
                        :error="form.errors.featured_image"
                    />
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <Link
                        :href="route('admin.galleries.index')"
                        class="inline-flex items-center justify-center rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                    >
                        Batal
                    </Link>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="inline-flex items-center justify-center rounded-lg bg-emerald-600 hover:bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors disabled:opacity-50"
                    >
                        {{ isEditing ? 'Simpan Perubahan' : 'Buat Galeri' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
