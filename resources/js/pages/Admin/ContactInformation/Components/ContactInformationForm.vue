<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import FormInput from '@/components/forms/FormInput.vue';
import FormSelect from '@/components/forms/FormSelect.vue';
import FormSwitch from '@/components/forms/FormSwitch.vue';
import FormTextarea from '@/components/forms/FormTextarea.vue';
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue';
import ImageUploader from '@/components/ui/upload/ImageUploader.vue';
import type { ContactInformation } from '@/types/contact-information';

const props = defineProps<{
    contact?: ContactInformation;
    isEditing?: boolean;
}>();

const form = useForm({
    title: props.contact?.title || '',
    excerpt: props.contact?.excerpt || '',
    description: props.contact?.description || '',
    address: props.contact?.address || '',
    google_maps_embed: props.contact?.google_maps_embed || '',
    google_maps_url: props.contact?.google_maps_url || '',
    phone: props.contact?.phone || '',
    mobile: props.contact?.mobile || '',
    whatsapp: props.contact?.whatsapp || '',
    email: props.contact?.email || '',
    website: props.contact?.website || '',
    facebook: props.contact?.facebook || '',
    instagram: props.contact?.instagram || '',
    youtube: props.contact?.youtube || '',
    tiktok: props.contact?.tiktok || '',
    office_hours: props.contact?.office_hours || '',
    emergency_contact: props.contact?.emergency_contact || '',
    featured_image_path: null as File | string | null,
    status: props.contact?.status || 'draft',
    is_pinned: props.contact?.is_pinned || false,
    sort_order: props.contact?.sort_order || 0,
});

const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Published', value: 'published' },
    { label: 'Archived', value: 'archived' },
];

const submit = () => {
    if (props.isEditing && props.contact) {
        form.transform((data) => ({
            ...data,
            _method: 'PUT',
        })).post(route('admin.contact-information.update', props.contact.ulid), {
            preserveScroll: true,
        });
    } else {
        form.post(route('admin.contact-information.store'), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kolom Utama -->
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Informasi Utama</h3>
                    
                    <FormInput
                        v-model="form.title"
                        label="Judul"
                        :error="form.errors.title"
                        required
                    />

                    <FormTextarea
                        v-model="form.excerpt"
                        label="Kutipan Singkat"
                        :error="form.errors.excerpt"
                        :rows="3"
                    />

                    <div class="mb-4">
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Lengkap</label>
                        <RichTextEditor
                            v-model="form.description"
                            :error="form.errors.description"
                        />
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Lokasi & Maps</h3>
                    
                    <FormTextarea
                        v-model="form.address"
                        label="Alamat Lengkap"
                        :error="form.errors.address"
                        :rows="3"
                    />

                    <FormInput
                        v-model="form.google_maps_url"
                        label="URL Google Maps"
                        :error="form.errors.google_maps_url"
                    />

                    <FormTextarea
                        v-model="form.google_maps_embed"
                        label="Kode Embed Google Maps (iframe)"
                        :error="form.errors.google_maps_embed"
                        :rows="4"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Kontak</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <FormInput
                            v-model="form.phone"
                            label="Telepon"
                            :error="form.errors.phone"
                        />
                        <FormInput
                            v-model="form.mobile"
                            label="No HP"
                            :error="form.errors.mobile"
                        />
                        <FormInput
                            v-model="form.whatsapp"
                            label="WhatsApp"
                            :error="form.errors.whatsapp"
                        />
                        <FormInput
                            v-model="form.email"
                            label="Email"
                            type="email"
                            :error="form.errors.email"
                        />
                        <FormInput
                            v-model="form.emergency_contact"
                            label="Kontak Darurat"
                            :error="form.errors.emergency_contact"
                        />
                        <FormInput
                            v-model="form.website"
                            label="Website"
                            :error="form.errors.website"
                        />
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Sosial Media & Jam Kerja</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <FormInput
                            v-model="form.facebook"
                            label="Facebook"
                            :error="form.errors.facebook"
                        />
                        <FormInput
                            v-model="form.instagram"
                            label="Instagram"
                            :error="form.errors.instagram"
                        />
                        <FormInput
                            v-model="form.youtube"
                            label="YouTube"
                            :error="form.errors.youtube"
                        />
                        <FormInput
                            v-model="form.tiktok"
                            label="TikTok"
                            :error="form.errors.tiktok"
                        />
                    </div>

                    <FormTextarea
                        v-model="form.office_hours"
                        label="Jam Kerja"
                        :error="form.errors.office_hours"
                        :rows="3"
                        placeholder="Senin - Jumat: 08:00 - 16:00"
                    />
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pengaturan</h3>
                    
                    <FormSelect
                        v-model="form.status"
                        label="Status"
                        :options="statusOptions"
                        :error="form.errors.status"
                        required
                    />

                    <div class="mt-4">
                        <FormSwitch
                            v-model="form.is_pinned"
                            label="Sematkan Kontak"
                            :error="form.errors.is_pinned"
                        />
                    </div>

                    <div class="mt-4">
                        <FormInput
                            v-model="form.sort_order"
                            label="Urutan"
                            type="number"
                            :error="form.errors.sort_order"
                        />
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Gambar Unggulan</h3>
                    
                    <ImageUploader
                        :model-value="null"
                        @update:modelValue="(val: File | null) => form.featured_image_path = val"
                        :current-image="props.contact?.featured_image_path || null"
                        :error="form.errors.featured_image_path"
                    />
                </div>

                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        @click="$inertia.visit(route('admin.contact-information.index'))"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</template>
