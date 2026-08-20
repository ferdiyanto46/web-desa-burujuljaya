<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { TourismCategory, TourismFacility } from '@/types';

interface Props {
    categories: { data: TourismCategory[] };
    facilities: { data: TourismFacility[] };
}

const props = defineProps<Props>();

const categoryList = computed(() => {
    return props.categories.data ?? [];
});

const facilityList = computed(() => {
    return props.facilities.data ?? [];
});

const form = useForm({
    category_id: '',
    title: '',
    excerpt: '',
    description: '',
    featured_image: null as File | null,
    address: '',
    latitude: '',
    longitude: '',
    google_maps_url: '',
    opening_hours: '',
    ticket_price: '',
    contact_person: '',
    phone: '',
    website: '',
    status: 'draft',
    is_featured: false,
    is_pinned: false,
    featured_order: 0,
    meta_title: '',
    meta_description: '',
    canonical_url: '',
    og_image: null as File | null,
    facilities: [] as number[],
    gallery: [] as File[],
});

const handleFileChange = (e: Event, field: 'featured_image' | 'og_image') => {
    const target = e.target as HTMLInputElement;

    if (target.files && target.files.length > 0) {
        form[field] = target.files[0];
    }
};

const handleGalleryChange = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        form.gallery = Array.from(target.files);
    }
};

const submit = () => {
    form.post(route('admin.tourism.store'));
};
</script>

<template>
    <Head title="Tambah Wisata" />

    <DashboardContainer>
        <template #header>
            <div class="flex flex-col">
                <button
                    @click="router.visit(route('admin.tourism.index'))"
                    class="mb-4 inline-flex w-fit items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    <ArrowLeftIcon class="mr-1 h-4 w-4" />
                    Kembali ke Daftar Wisata
                </button>
                <PageTitle 
                    title="Tambah Destinasi Wisata" 
                    description="Tambahkan destinasi wisata baru desa."
                />
            </div>
        </template>

        <div class="mt-6 bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form @submit.prevent="submit" class="space-y-6">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                        <select v-model="form.category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="">Pilih Kategori</option>
                            <option v-for="cat in categoryList" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                        </select>
                        <div v-if="form.errors.category_id" class="text-red-500 text-sm mt-1">{{ form.errors.category_id }}</div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Wisata</label>
                        <input type="text" v-model="form.title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ringkasan (Excerpt)</label>
                    <textarea v-model="form.excerpt" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    <div v-if="form.errors.excerpt" class="text-red-500 text-sm mt-1">{{ form.errors.excerpt }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi Lengkap</label>
                    <textarea v-model="form.description" rows="5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                    <div v-if="form.errors.description" class="text-red-500 text-sm mt-1">{{ form.errors.description }}</div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Image</label>
                    <input type="file" @change="e => handleFileChange(e, 'featured_image')" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    <div v-if="form.errors.featured_image" class="text-red-500 text-sm mt-1">{{ form.errors.featured_image }}</div>
                </div>

                <!-- Location -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Lokasi</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Alamat</label>
                            <input type="text" v-model="form.address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Latitude</label>
                            <input type="text" v-model="form.latitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Longitude</label>
                            <input type="text" v-model="form.longitude" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">URL Google Maps</label>
                            <input type="url" v-model="form.google_maps_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Informasi Tambahan</h3>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Jam Operasional</label>
                            <input type="text" v-model="form.opening_hours" placeholder="Contoh: 08:00 - 17:00" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga Tiket</label>
                            <input type="text" v-model="form.ticket_price" placeholder="Contoh: Rp 10.000" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contact Person</label>
                            <input type="text" v-model="form.contact_person" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nomor Telepon</label>
                            <input type="text" v-model="form.phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website</label>
                            <input type="url" v-model="form.website" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>
                </div>

                <!-- Facilities & Gallery -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Fasilitas & Galeri</h3>
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Fasilitas Tersedia</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="facility in facilityList" :key="facility.id" class="flex items-center">
                                <input type="checkbox" :value="facility.id" v-model="form.facilities" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" />
                                <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">{{ facility.name }}</label>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Galeri Foto (Bisa pilih banyak)</label>
                        <input type="file" multiple @change="handleGalleryChange" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                    </div>
                </div>

                <!-- Publishing & SEO -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Pengaturan & SEO</h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                            <select v-model="form.status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="draft">Draft</option>
                                <option value="published">Published</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Featured Order</label>
                            <input type="number" v-model="form.featured_order" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                    </div>

                    <div class="flex space-x-6 mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" v-model="form.is_featured" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                            <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Jadikan Featured</label>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" v-model="form.is_pinned" class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500" />
                            <label class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Sematkan (Pin)</label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Title</label>
                            <input type="text" v-model="form.meta_title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Meta Description</label>
                            <textarea v-model="form.meta_description" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Canonical URL</label>
                            <input type="url" v-model="form.canonical_url" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">OG Image</label>
                            <input type="file" @change="e => handleFileChange(e, 'og_image')" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        </div>
                    </div>
                </div>

                <div class="pt-5 border-t border-gray-200 dark:border-gray-700 mt-6 flex justify-end">
                    <button type="button" @click="router.visit(route('admin.tourism.index'))" class="bg-white dark:bg-gray-700 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" :disabled="form.processing" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50">
                        Simpan Wisata
                    </button>
                </div>
            </form>
        </div>
    </DashboardContainer>
</template>
