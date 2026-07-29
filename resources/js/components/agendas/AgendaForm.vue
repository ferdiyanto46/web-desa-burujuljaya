<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { SaveIcon, XIcon } from 'lucide-vue-next';
import type { Agenda } from '@/types';
import FormDatePicker from '../forms/FormDatePicker.vue';
import FormInput from '../forms/FormInput.vue';
import FormSelect from '../forms/FormSelect.vue';
import FormSwitch from '../forms/FormSwitch.vue';
import RichTextEditor from '../ui/editor/RichTextEditor.vue';
import ImageUploader from '../ui/upload/ImageUploader.vue';

interface Props {
    agenda?: Agenda | null;
    submitRoute: string;
    cancelRoute: string;
    isEdit?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    agenda: null,
    isEdit: false,
});

const form = useForm({
    title: props.agenda?.title || '',
    content: props.agenda?.content || '',
    location: props.agenda?.location || '',
    location_url: props.agenda?.location_url || '',
    start_datetime: props.agenda?.start_datetime ? props.agenda.start_datetime.substring(0, 16) : '',
    end_datetime: props.agenda?.end_datetime ? props.agenda.end_datetime.substring(0, 16) : '',
    status: props.agenda?.status || 'draft',
    is_cancelled: props.agenda?.is_cancelled || false,
    published_at: props.agenda?.published_at ? props.agenda.published_at.substring(0, 16) : '',
    featured_image: null as File | null,
    // Inertia needs _method for PUT requests with files
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
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Informasi Agenda</h3>
                    
                    <FormInput
                        v-model="form.title"
                        label="Nama Kegiatan / Agenda"
                        placeholder="Contoh: Rapat Koordinasi Desa"
                        :error="form.errors.title"
                        required
                    />
                    
                    <div class="mt-6">
                        <RichTextEditor
                            v-model="form.content"
                            label="Deskripsi Kegiatan"
                            :error="form.errors.content"
                            required
                        />
                    </div>
                </div>
                
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Waktu & Lokasi</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <FormDatePicker
                            v-model="form.start_datetime"
                            label="Waktu Mulai"
                            type="datetime-local"
                            :error="form.errors.start_datetime"
                            required
                        />
                        
                        <FormDatePicker
                            v-model="form.end_datetime"
                            label="Waktu Selesai (Opsional)"
                            type="datetime-local"
                            :error="form.errors.end_datetime"
                        />
                    </div>
                    
                    <div class="mt-6">
                        <FormInput
                            v-model="form.location"
                            label="Lokasi Kegiatan"
                            placeholder="Contoh: Balai Desa Burujul Jaya"
                            :error="form.errors.location"
                            required
                        />
                    </div>
                    
                    <div class="mt-6">
                        <FormInput
                            v-model="form.location_url"
                            label="URL Google Maps (Opsional)"
                            placeholder="https://goo.gl/maps/..."
                            :error="form.errors.location_url"
                        />
                    </div>
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
                            required
                        />
                        
                        <div v-if="isEdit" class="pt-4 border-t border-gray-200 dark:border-gray-700">
                            <FormSwitch
                                v-model="form.is_cancelled"
                                label="Batalkan Kegiatan"
                                description="Tandai kegiatan ini sebagai batal"
                                :error="form.errors.is_cancelled"
                            />
                        </div>
                    </div>
                </div>
                
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="mb-5 text-lg font-medium text-gray-900 dark:text-gray-100">Poster / Banner</h3>
                    
                    <ImageUploader
                        v-model="form.featured_image"
                        :image-url="agenda?.featured_image_path ? `/storage/${agenda.featured_image_path}` : null"
                        label="Upload Poster Kegiatan"
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
                {{ form.processing ? 'Menyimpan...' : 'Simpan Agenda' }}
            </button>
        </div>
    </form>
</template>
