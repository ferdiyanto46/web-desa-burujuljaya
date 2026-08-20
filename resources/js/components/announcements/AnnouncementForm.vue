<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { SaveIcon, XIcon } from 'lucide-vue-next';
import type { Announcement } from '@/types/announcement';
import FormDatePicker from '../forms/FormDatePicker.vue';
import FormInput from '../forms/FormInput.vue';
import FormSelect from '../forms/FormSelect.vue';
import FormSwitch from '../forms/FormSwitch.vue';
import FormTextarea from '../forms/FormTextarea.vue';
import RichTextEditor from '../ui/editor/RichTextEditor.vue';
import ImageUploader from '../ui/upload/ImageUploader.vue';

interface Props {
    announcement?: Announcement | null;
    submitRoute: string;
    cancelRoute: string;
    isEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    announcement: null,
    isEdit: false,
});

const form = useForm({
    title: props.announcement?.title || '',
    excerpt: props.announcement?.excerpt || '',
    content: props.announcement?.content || '',
    featured_image: null as File | null,
    status: props.announcement?.status || 'draft',
    is_pinned: props.announcement?.is_pinned || false,
    published_at: props.announcement?.published_at ? props.announcement.published_at.slice(0, 16) : '',
    _method: props.isEdit ? 'PUT' : 'POST',
});

const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Published', value: 'published' },
    { label: 'Archived', value: 'archived' },
];

const submit = () => {
    form.post(props.submitRoute, {
        preserveScroll: true,
    });
};

const cancel = () => {
    window.location.href = props.cancelRoute;
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-8">
        <div class="grid grid-cols-1 gap-y-6 gap-x-8 lg:grid-cols-3">
            
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Dasar</h3>
                    
                    <FormInput
                        v-model="form.title"
                        label="Judul Pengumuman"
                        placeholder="Masukkan judul pengumuman"
                        :error="form.errors.title"
                        required
                    />
                    
                    <FormTextarea
                        v-model="form.excerpt"
                        label="Ringkasan (Excerpt)"
                        placeholder="Ringkasan singkat pengumuman"
                        :error="form.errors.excerpt"
                        :rows="3"
                    />
                    
                    <RichTextEditor
                        v-model="form.content"
                        label="Isi Pengumuman"
                        :error="form.errors.content"
                        required
                    />
                </div>
            </div>
            
            <!-- Sidebar Area -->
            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Pengaturan Publikasi</h3>
                    
                    <div class="space-y-4">
                        <FormSelect
                            v-model="form.status"
                            label="Status Publikasi"
                            :options="statusOptions"
                            :error="form.errors.status"
                            required
                        />
                        
                        <FormDatePicker
                            v-if="form.status === 'published'"
                            v-model="form.published_at"
                            label="Tanggal Publikasi"
                            type="datetime-local"
                            :error="form.errors.published_at"
                        />
                        
                        <div class="pt-2">
                            <FormSwitch
                                v-model="form.is_pinned"
                                label="Sematkan (Pin)"
                                description="Tampilkan pengumuman ini di urutan paling atas"
                                :error="form.errors.is_pinned"
                            />
                        </div>
                    </div>
                </div>
                
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Gambar Pengumuman</h3>
                    
                    <ImageUploader
                        v-model="form.featured_image"
                        :image-url="announcement?.featured_image_url || null"
                        label="Gambar Banner (Opsional)"
                        :error="form.errors.featured_image"
                    />
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end space-x-4 border-t border-gray-200 pt-6 dark:border-gray-700">
            <button
                type="button"
                @click="cancel"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                <XIcon class="-ml-1 mr-2 h-4 w-4" />
                Batal
            </button>
            <button
                type="submit"
                :disabled="form.processing"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
            >
                <SaveIcon class="-ml-1 mr-2 h-4 w-4" />
                {{ form.processing ? 'Menyimpan...' : 'Simpan Pengumuman' }}
            </button>
        </div>
    </form>
</template>
