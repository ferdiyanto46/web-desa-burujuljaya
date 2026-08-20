<script setup lang="ts">
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ArrowLeft, Save, TrashIcon } from 'lucide-vue-next'
import { computed } from 'vue'
import FormInput from '@/components/forms/FormInput.vue'
import FormSelect from '@/components/forms/FormSelect.vue'
import FormSwitch from '@/components/forms/FormSwitch.vue'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import RichTextEditor from '@/components/ui/editor/RichTextEditor.vue'
import ImageUploader from '@/components/ui/upload/ImageUploader.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { Product, ProductCategory } from '@/types/product'
import type { Umkm } from '@/types/umkm'

const props = defineProps<{
    product: Product
    categories: { data: ProductCategory[] }
    umkms: { data: Umkm[] }
}>()

const form = useForm({
    title: props.product.title || '',
    category_id: props.product.category_id?.toString() || '',
    umkm_id: props.product.umkm_id?.toString() || '',
    description: props.product.description || '',
    price: props.product.price || '',
    status: props.product.status,
    featured_image: null as File | null,
    gallery: [] as File[],
    _method: 'PUT',
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
    form.post(route('admin.products.update', props.product.ulid))
}

const deletePhoto = (photoId: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus foto ini?')) {
        router.delete(route('admin.products.photos.destroy', { product: props.product.ulid, photo: photoId }), {
            preserveScroll: true
        })
    }
}
</script>

<template>
    <Head title="Edit Produk" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="route('admin.products.index')">
                        <ArrowLeft class="w-4 h-4" />
                    </Link>
                </Button>
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Edit Produk</h2>
                    <p class="text-sm text-muted-foreground">
                        Perbarui data produk UMKM.
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
                                <FormSelect
                                    v-model="form.status"
                                    :options="[
                                        { value: 'published', label: 'Dipublikasikan' },
                                        { value: 'draft', label: 'Draft' },
                                        { value: 'archived', label: 'Diarsipkan' }
                                    ]"
                                    label="Status"
                                    :error="form.errors.status"
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
                                :image-url="product.featured_image_path ? `/storage/${product.featured_image_path}` : null"
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
                            <div v-if="product.photos && product.photos.length > 0" class="mb-4 grid grid-cols-3 gap-2">
                                <div v-for="photo in product.photos" :key="photo.id" class="relative group">
                                    <img :src="`/storage/${photo.image_path}`" class="w-full h-20 object-cover rounded-md" />
                                    <button 
                                        type="button" 
                                        @click="deletePhoto(photo.id)" 
                                        class="absolute top-1 right-1 bg-red-500 text-white p-1 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"
                                    >
                                        <TrashIcon class="w-3 h-3" />
                                    </button>
                                </div>
                            </div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tambah Gambar</label>
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
                    {{ form.processing ? 'Menyimpan...' : 'Perbarui Produk' }}
                </Button>
            </div>
        </form>
    </AdminLayout>
</template>
