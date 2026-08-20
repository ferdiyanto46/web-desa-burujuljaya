<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeft, Save } from 'lucide-vue-next'
import { computed } from 'vue'
import FormDatePicker from '@/components/forms/FormDatePicker.vue'
import FormInput from '@/components/forms/FormInput.vue'
import FormSelect from '@/components/forms/FormSelect.vue'
import FormSwitch from '@/components/forms/FormSwitch.vue'
import FormTextarea from '@/components/forms/FormTextarea.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue'
import ImageUploader from '@/components/ui/upload/ImageUploader.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { UmkmCategory } from '@/types/umkm'

const props = defineProps<{
    categories: { data: UmkmCategory[] }
}>()

const form = useForm({
    title: '',
    category_id: '',
    owner_name: '',
    description: '',
    address: '',
    latitude: '',
    longitude: '',
    google_maps_url: '',
    phone: '',
    whatsapp: '',
    email: '',
    website: '',
    instagram: '',
    facebook: '',
    operating_hours: '',
    status: 'draft',
    is_featured: false,
    is_pinned: false,
    featured_order: 0,
    featured_image: null as File | null,
    gallery: [] as File[],
})

const statusOptions = [
    { label: 'Draft', value: 'draft' },
    { label: 'Published', value: 'published' },
    { label: 'Archived', value: 'archived' },
]

const categoryOptions = computed(() => {
    return (props.categories.data ?? []).map(c => ({
        label: c.name,
        value: c.id.toString(),
    }))
})

const handleGalleryChange = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        form.gallery = Array.from(target.files);
    }
}

const submit = () => {
    form.post(route('admin.umkm.store'))
}
</script>

<template>
    <Head title="Tambah UMKM" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="route('admin.umkm.index')">
                        <ArrowLeft class="w-4 h-4" />
                    </Link>
                </Button>
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Tambah UMKM</h2>
                    <p class="text-sm text-muted-foreground">
                        Daftarkan data UMKM baru di desa.
                    </p>
                </div>
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Main Form -->
                <div class="md:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Dasar</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <FormInput
                                v-model="form.title"
                                label="Nama UMKM"
                                :error="form.errors.title"
                                required
                            />
                            
                            <FormInput
                                v-model="form.owner_name"
                                label="Nama Pemilik"
                                :error="form.errors.owner_name"
                            />
                            
                            <RichTextEditor
                                v-model="form.description"
                                label="Deskripsi UMKM"
                                :error="form.errors.description"
                                required
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Lokasi</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <FormTextarea
                                v-model="form.address"
                                label="Alamat Lengkap"
                                :error="form.errors.address"
                                :rows="3"
                            />
                            
                            <div class="grid grid-cols-2 gap-4">
                                <FormInput
                                    v-model="form.latitude"
                                    label="Latitude"
                                    :error="form.errors.latitude"
                                />
                                <FormInput
                                    v-model="form.longitude"
                                    label="Longitude"
                                    :error="form.errors.longitude"
                                />
                            </div>
                            
                            <FormInput
                                v-model="form.google_maps_url"
                                label="URL Google Maps"
                                type="url"
                                :error="form.errors.google_maps_url"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Kontak & Media Sosial</CardTitle>
                        </CardHeader>
                        <CardContent class="grid grid-cols-2 gap-4">
                            <FormInput
                                v-model="form.phone"
                                label="Nomor Telepon"
                                :error="form.errors.phone"
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
                                v-model="form.website"
                                label="Website"
                                type="url"
                                :error="form.errors.website"
                            />
                            <FormInput
                                v-model="form.instagram"
                                label="Instagram"
                                :error="form.errors.instagram"
                            />
                            <FormInput
                                v-model="form.facebook"
                                label="Facebook"
                                :error="form.errors.facebook"
                            />
                            <div class="col-span-2">
                                <FormInput
                                    v-model="form.operating_hours"
                                    label="Jam Operasional"
                                    placeholder="Contoh: Senin - Jumat (08:00 - 17:00)"
                                    :error="form.errors.operating_hours"
                                />
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar Form -->
                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Pengaturan & Kategori</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <FormSelect
                                v-model="form.status"
                                label="Status Publikasi"
                                :options="statusOptions"
                                :error="form.errors.status"
                                required
                            />
                            
                            <FormSelect
                                v-model="form.category_id"
                                label="Kategori"
                                :options="categoryOptions"
                                :error="form.errors.category_id"
                                placeholder="Pilih Kategori"
                                required
                            />
                            
                            <div class="pt-2 space-y-4">
                                <FormSwitch
                                    v-model="form.is_featured"
                                    label="Jadikan Featured"
                                    description="Tampilkan sebagai UMKM unggulan"
                                    :error="form.errors.is_featured"
                                />
                                <FormSwitch
                                    v-model="form.is_pinned"
                                    label="Sematkan (Pin)"
                                    :error="form.errors.is_pinned"
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Media Utama</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ImageUploader
                                v-model="form.featured_image"
                                label="Gambar Utama"
                                :error="form.errors.featured_image"
                            />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Galeri Tambahan</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload Banyak Gambar</label>
                            <input 
                                type="file" 
                                multiple 
                                @change="handleGalleryChange" 
                                accept="image/*" 
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" 
                            />
                        </CardContent>
                    </Card>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <Button type="button" variant="outline" as-child>
                    <Link :href="route('admin.umkm.index')">Batal</Link>
                </Button>
                <Button type="submit" :disabled="form.processing">
                    <Save class="w-4 h-4 mr-2" />
                    Simpan UMKM
                </Button>
            </div>
        </form>
    </AdminLayout>
</template>
