<script setup lang="ts">
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { SaveIcon, XIcon } from 'lucide-vue-next';
import FormInput from '@/components/forms/FormInput.vue';
import FormSelect from '@/components/forms/FormSelect.vue';
import FormSwitch from '@/components/forms/FormSwitch.vue';
import FormTextarea from '@/components/forms/FormTextarea.vue';
import FileUploader from '@/components/ui/upload/FileUploader.vue';
import { route } from '@/lib/route';
import type { Document, DocumentCategory } from '@/types/document';

interface Props {
    document?: Document;
    categories: DocumentCategory[] | { data: DocumentCategory[] };
}

const props = defineProps<Props>();

const categoryOptions = computed(() => {
    const list = Array.isArray(props.categories) ? props.categories : ((props.categories as any)?.data ?? []);
    return list.map((c: any) => ({ label: c.name, value: c.id }));
});

const form = useForm({
    title: props.document?.title ?? '',
    category_id: props.document?.category_id ?? (props.document?.category?.id ?? ''),
    description: props.document?.description ?? '',
    status: props.document?.status ?? 'published',
    is_pinned: props.document?.is_pinned ?? false,
    file: null as File | null,
    _method: props.document ? 'PUT' : 'POST',
});

const statusOptions = [
    { label: 'Published (Publikasikan)', value: 'published' },
    { label: 'Draft (Draf)', value: 'draft' },
    { label: 'Archived (Arsipkan)', value: 'archived' },
];

const submit = () => {
    if (props.document) {
        form.post(route('admin.documents.update', props.document.ulid), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.documents.store'), {
            preserveScroll: true,
        });
    }
};

const cancel = () => {
    window.location.href = route('admin.documents.index');
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <!-- Title -->
                <FormInput
                    v-model="form.title"
                    label="Judul Dokumen"
                    placeholder="Masukkan judul dokumen"
                    :error="form.errors.title"
                    required
                />

                <!-- Description -->
                <FormTextarea
                    v-model="form.description"
                    label="Deskripsi"
                    placeholder="Deskripsi singkat tentang dokumen ini"
                    :error="form.errors.description"
                    :rows="4"
                />
                
                <!-- File Input -->
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <FileUploader
                        v-model="form.file"
                        :file-name="document?.file_name"
                        :file-url="document?.file_url"
                        label="File Dokumen"
                        helper-text="PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP up to 10MB"
                        :error="form.errors.file"
                    />
                </div>
            </div>

            <div class="space-y-6">
                <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 space-y-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Pengaturan Dokumen</h3>
                    
                    <!-- Status -->
                    <FormSelect
                        v-model="form.status"
                        label="Status Publikasi"
                        :options="statusOptions"
                        :error="form.errors.status"
                        required
                    />

                    <!-- Category -->
                    <FormSelect
                        v-model="form.category_id"
                        label="Kategori Dokumen"
                        :options="categoryOptions"
                        :error="form.errors.category_id"
                        required
                    />

                    <!-- Pinned -->
                    <div class="pt-2">
                        <FormSwitch
                            v-model="form.is_pinned"
                            label="Sematkan (Pin)"
                            description="Tampilkan dokumen ini di urutan paling atas"
                            :error="form.errors.is_pinned"
                        />
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 border-t border-gray-200 dark:border-gray-700 pt-6">
            <button
                type="button"
                @click="cancel"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
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
                {{ form.processing ? 'Menyimpan...' : 'Simpan Dokumen' }}
            </button>
        </div>
    </form>
</template>
