<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, Loader2, Save, UploadCloud, Image as ImageIcon, X, MapPin } from 'lucide-vue-next';
import { ref } from 'vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Switch } from '@/components/ui/switch';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface HomepageSection {
    id: number;
    ulid: string;
    section_key: string;
    title: string | null;
    subtitle: string | null;
    content: string | null;
    configuration: Record<string, any> | null;
    sort_order: number;
    is_enabled: boolean;
    status: string;
}

const props = defineProps<{
    section: HomepageSection;
}>();

const existingBannerUrl = props.section.configuration?.banner_image_url 
    || (props.section.configuration?.banner_image_path ? `/storage/${props.section.configuration.banner_image_path.replace(/^\//, '')}` : null);

const previewUrl = ref<string | null>(existingBannerUrl || (props.section.section_key === 'hero_slider' ? '/images/hero-bg.jpg' : null));
const fileInputRef = ref<HTMLInputElement | null>(null);
const isDragging = ref(false);

const form = useForm({
    title: props.section.title || '',
    subtitle: props.section.subtitle || '',
    content: props.section.content || '',
    configuration: props.section.configuration || {},
    banner_image: null as File | null,
    is_enabled: Boolean(props.section.is_enabled),
    _method: 'PUT',
});

const handleFileSelect = (file: File) => {
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        alert('Format file harus berupa gambar (JPG, PNG, atau WEBP).');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5 MB.');
        return;
    }
    form.banner_image = file;
    previewUrl.value = URL.createObjectURL(file);
};

const onFileInputChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        handleFileSelect(target.files[0]);
    }
};

const onDrop = (e: DragEvent) => {
    isDragging.value = false;
    if (e.dataTransfer?.files && e.dataTransfer.files[0]) {
        handleFileSelect(e.dataTransfer.files[0]);
    }
};

const removeSelectedImage = () => {
    form.banner_image = null;
    previewUrl.value = existingBannerUrl || '/images/hero-bg.jpg';
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
};

const submit = () => {
    form.transform((data) => ({
        ...data,
        _method: 'PUT',
    })).post(route('admin.homepage-sections.update', props.section.ulid), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <Head :title="`Konfigurasi ${section.title || section.section_key}`" />

        <div class="space-y-6 max-w-4xl mx-auto pb-12">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('admin.homepage-sections.index')">
                        <Button variant="ghost" size="sm" class="h-9 w-9 p-0 text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <ArrowLeft class="w-5 h-5" />
                        </Button>
                    </Link>
                    <PageTitle 
                        :title="`Konfigurasi: ${section.title || section.section_key}`" 
                        subtitle="Sesuaikan judul, deskripsi pembuka, gambar latar, dan visibilitas seksi beranda" 
                    />
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 max-w-3xl">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Title -->
                    <div class="space-y-1.5">
                        <Label for="title" class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                            {{ section.section_key === 'hero_slider' ? 'Judul Sambutan Hero Banner' : 'Judul Seksi di Beranda' }}
                        </Label>
                        <Input 
                            id="title" 
                            v-model="form.title" 
                            type="text" 
                            :placeholder="section.section_key === 'hero_slider' ? 'Contoh: Selamat Datang di Desa Burujuljaya' : 'Contoh: Berita & Kabar Desa'"
                            class="h-10"
                        />
                        <p v-if="form.errors.title" class="text-xs text-red-500">{{ form.errors.title }}</p>
                    </div>

                    <!-- Subtitle -->
                    <div class="space-y-1.5">
                        <Label for="subtitle" class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                            {{ section.section_key === 'hero_slider' ? 'Subjudul / Deskripsi Sambutan' : 'Subjudul / Deskripsi Pembuka' }}
                        </Label>
                        <Input 
                            id="subtitle" 
                            v-model="form.subtitle" 
                            type="text" 
                            placeholder="Contoh: Pusat informasi publik dan transparansi pembangunan desa"
                            class="h-10"
                        />
                        <p v-if="form.errors.subtitle" class="text-xs text-red-500">{{ form.errors.subtitle }}</p>
                    </div>

                    <!-- Hero Banner Image Upload (Only for hero_slider) -->
                    <div v-if="section.section_key === 'hero_slider'" class="space-y-3">
                        <Label class="text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Gambar Latar Belakang Hero Banner (*Hero Image*)
                        </Label>

                        <input 
                            ref="fileInputRef" 
                            type="file" 
                            accept="image/png,image/jpeg,image/jpg,image/webp" 
                            class="hidden" 
                            @change="onFileInputChange" 
                        />

                        <!-- Upload / Preview Box -->
                        <div 
                            class="relative border-2 border-dashed rounded-xl p-4 transition-colors duration-200 text-center cursor-pointer flex flex-col items-center justify-center overflow-hidden"
                            :class="[
                                isDragging 
                                    ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' 
                                    : 'border-gray-200 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/30 hover:border-emerald-400'
                            ]"
                            @click="fileInputRef?.click()"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="onDrop"
                        >
                            <!-- Preview Image if available -->
                            <div v-if="previewUrl" class="w-full relative mb-3 rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 bg-gray-900 aspect-[16/9] max-h-64">
                                <img 
                                    :src="previewUrl" 
                                    alt="Preview Hero Banner" 
                                    class="w-full h-full object-cover"
                                />
                                <div class="absolute inset-0 bg-black/30 hover:bg-black/10 transition-colors flex items-center justify-center">
                                    <span class="bg-black/70 text-white text-xs font-medium px-3 py-1.5 rounded-md backdrop-blur-sm">
                                        Klik atau seret gambar baru untuk mengganti
                                    </span>
                                </div>
                                <button 
                                    v-if="form.banner_image"
                                    type="button" 
                                    @click.stop="removeSelectedImage" 
                                    class="absolute top-2 right-2 p-1.5 bg-red-600 hover:bg-red-700 text-white rounded-full shadow-md transition-colors"
                                    title="Batalkan pilihan gambar"
                                >
                                    <X class="w-4 h-4" />
                                </button>
                            </div>

                            <!-- Placeholder Icon and Text -->
                            <div v-else class="py-6 flex flex-col items-center">
                                <div class="w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                                    <UploadCloud class="w-6 h-6" />
                                </div>
                                <p class="text-sm font-medium text-gray-700 dark:text-gray-200">
                                    Klik untuk memilih gambar atau seret file ke sini
                                </p>
                                <p class="text-xs text-gray-400 mt-1">
                                    Format: JPG, PNG, WEBP. Maksimal 5 MB. Rekomendasi rasio 16:9 (1920x1080).
                                </p>
                            </div>
                        </div>

                        <p v-if="form.errors.banner_image" class="text-xs text-red-500">{{ form.errors.banner_image }}</p>
                    </div>

                    <!-- Configuration Limit (Only for list sections) -->
                    <div class="space-y-1.5" v-if="['featured_articles', 'announcements', 'gallery', 'tourism', 'umkm', 'village_products', 'village_services', 'faq', 'contact_preview'].includes(section.section_key)">
                        <Label for="limit" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Jumlah Maksimal Item Ditampilkan</Label>
                        <Input 
                            id="limit" 
                            v-model.number="form.configuration.limit" 
                            type="number" 
                            min="1"
                            max="24"
                            placeholder="Contoh: 6"
                            class="h-10 max-w-xs"
                        />
                        <p class="text-[11px] text-gray-400">Berapa banyak kartu konten yang muncul pada seksi ini di halaman beranda.</p>
                    </div>

                    <!-- Map Configuration (Only for map) -->
                    <div v-if="section.section_key === 'map'" class="space-y-4 pt-2 border-t border-gray-100 dark:border-gray-800">
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <MapPin class="w-4 h-4 text-emerald-600" />
                            Konfigurasi Titik Koordinat & Peta Desa
                        </h4>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <Label for="latitude" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Latitude (Garis Lintang)</Label>
                                <Input 
                                    id="latitude" 
                                    v-model="form.configuration.latitude" 
                                    type="text" 
                                    placeholder="Contoh: -6.8362145"
                                    class="h-10 text-xs font-mono"
                                />
                            </div>
                            <div class="space-y-1.5">
                                <Label for="longitude" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Longitude (Garis Bujur)</Label>
                                <Input 
                                    id="longitude" 
                                    v-model="form.configuration.longitude" 
                                    type="text" 
                                    placeholder="Contoh: 108.2287412"
                                    class="h-10 text-xs font-mono"
                                />
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <Label for="embed_url" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Link Iframe Embed Google Maps (Opsional)</Label>
                            <Input 
                                id="embed_url" 
                                v-model="form.configuration.embed_url" 
                                type="text" 
                                placeholder="Contoh: https://www.google.com/maps/embed?pb=..."
                                class="h-10 text-xs font-mono"
                            />
                            <p class="text-[11px] text-gray-400">
                                Jika link embed dikosongkan, peta akan otomatis merender titik Latitude & Longitude atau alamat kantor desa secara interaktif.
                            </p>
                        </div>
                    </div>

                    <!-- Enable/Disable Switch -->
                    <div class="flex flex-row items-center justify-between rounded-xl border border-gray-200 dark:border-gray-800 p-4 bg-gray-50/50 dark:bg-gray-800/40">
                        <div class="space-y-0.5">
                            <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Tampilkan Seksi Ini di Beranda</span>
                            <p class="text-xs text-gray-400">
                                Jika dinonaktifkan, seksi ini akan disembunyikan dari pengunjung website publik.
                            </p>
                        </div>
                        <Switch
                            :checked="form.is_enabled"
                            @update:checked="(val: boolean) => form.is_enabled = val"
                        />
                    </div>

                    <!-- Actions -->
                    <div class="pt-2 flex items-center justify-end gap-3">
                        <Link :href="route('admin.homepage-sections.index')">
                            <Button type="button" variant="outline">
                                Batal
                            </Button>
                        </Link>

                        <Button 
                            type="submit" 
                            variant="default"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-2"
                            :disabled="form.processing"
                        >
                            <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                            <Save v-else class="w-4 h-4" />
                            <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
