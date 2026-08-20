<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3'
import { ArrowLeft, Save } from 'lucide-vue-next'
import { computed } from 'vue'
import FormInput from '@/components/forms/FormInput.vue'
import FormSelect from '@/components/forms/FormSelect.vue'
import FormSwitch from '@/components/forms/FormSwitch.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue'
import ImageUploader from '@/components/ui/upload/ImageUploader.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { ProductCategory } from '@/types/product'
import type { Umkm } from '@/types/umkm'

const props = defineProps<{
    categories: { data: ProductCategory[] }
    umkms: { data: Umkm[] }
}>()

const form = useForm({
    title: '',
    category_id: '',
    umkm_id: '',
    description: '',
    price: '',
    is_active: true,
    featured_image: null as File | null,
    gallery: [] as File[],
})

const categoryOptions = computed(() => {
    return (props.categories.data ?? []).map(c => ({
        label: c.name,
        value: c.id.toString(),
    }))
})

const umkmOptions = computed(() => {
    return (props.umkms.data ?? []).map(u => ({
        label: u.title,
        value: u.id.toString(),
    }))
})

const handleGalleryChange = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        form.gallery = Array.from(target.files);
    }
}

const submit = () => {
    form.post(route('admin.products.store'))
}
</script>

<template>
    <Head title="Tambah Produk" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="route('admin.products.index')">
                        <ArrowLeft class="w-4 h-4" />
                    </Link>
                </Button>
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Tambah Produk</h2>
                    <p class="text-sm text-muted-foreground">
                        Tambahkan data produk baru dari UMKM.
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
                                label="Nama Produk"
                                :error="form.errors.title"
                                required
                            />
                            
                            <FormInput
                                v-model="form.price"
                                label="Harga Produk"
                                type="number"
                                :error="form.errors.price"
                                required
                            />
                            
                            <RichTextEditor
                                v-model="form.description"
                                label="Deskripsi Produk"
                                :error="form.errors.description"
                                required
                            />
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
                                v-model="form.umkm_id"
                                label="UMKM Pemilik"
                                :options="umkmOptions"
                                :error="form.errors.umkm_id"
                                placeholder="Pilih UMKM"
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
                                    v-model="form.is_active"
                                    label="Status Aktif"
                                    description="Tampilkan produk ini"
                                    :error="form.errors.is_active"
                                />
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Gambar Utama</CardTitle>
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
                    <Link :href="route('admin.products.index')">Batal</Link>
                </Button>
                <Button type="submit" :disabled="form.processing">
                    <Save class="w-4 h-4 mr-2" />
                    Simpan Produk
                </Button>
            </div>
        </form>
    </AdminLayout>
</template>
