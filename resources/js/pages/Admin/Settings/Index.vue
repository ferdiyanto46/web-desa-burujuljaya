<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { UploadCloud, Image as ImageIcon, X, Trash2, CheckCircle2 } from 'lucide-vue-next';
import { ref, onMounted } from 'vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Icons } from '@/constants/icons';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface SettingItem {
    key: string;
    group: string;
    label: string;
    description: string | null;
    type: string;
    value: string | null;
    is_editable: boolean;
    is_public: boolean;
    is_encrypted?: boolean;
    options: string[] | Record<string, string> | null;
}

const props = defineProps<{
    settings: Record<string, SettingItem[]>;
}>();

// Initialize form with standard setting values
const initialData: Record<string, any> = {};
Object.values(props.settings).forEach((groupSettings) => {
    groupSettings.forEach((setting) => {
        if (setting.is_editable) {
            initialData[setting.key] = setting.value;
        }
    });
});

interface BannerItemConfig {
    title: string;
    subtitle: string;
    image_url?: string | null;
    image_path?: string | null;
}

const bannerConfigs = ref<Record<string, BannerItemConfig>>({});
const bannerFiles = ref<Record<string, File | null>>({});
const bannerPreviews = ref<Record<string, string | null>>({});

const pageBannerDefinitions: Array<{ key: string; label: string; routePath: string; defaultTitle: string; defaultSubtitle: string }> = [
    { key: 'profile', label: 'Profil & Struktur Desa', routePath: '/profil', defaultTitle: 'Profil & Struktur Organisasi', defaultSubtitle: 'Mengenal sejarah, visi-misi, dan susunan aparatur pemerintah desa.' },
    { key: 'articles', label: 'Kabar & Berita Desa', routePath: '/artikel', defaultTitle: 'Kabar Desa', defaultSubtitle: 'Informasi, berita, dan artikel terbaru seputar kegiatan desa.' },
    { key: 'gallery', label: 'Galeri Foto Kegiatan', routePath: '/galeri', defaultTitle: 'Galeri Desa', defaultSubtitle: 'Dokumentasi foto dan rekaman visual kegiatan masyarakat desa.' },
    { key: 'umkm', label: 'UMKM & Usaha Warga', routePath: '/umkm', defaultTitle: 'UMKM Desa', defaultSubtitle: 'Potensi usaha mikro, kecil, dan menengah masyarakat desa.' },
    { key: 'documents', label: 'Dokumen & Transparansi', routePath: '/dokumen', defaultTitle: 'Dokumen Publik', defaultSubtitle: 'Pusat unduhan dokumen resmi, formulir, dan regulasi desa.' },
    { key: 'complaints', label: 'Pengaduan Masyarakat', routePath: '/pengaduan', defaultTitle: 'Layanan Pengaduan', defaultSubtitle: 'Sampaikan laporan, aspirasi, kritik, dan saran untuk kemajuan desa.' },
    { key: 'tourism', label: 'Wisata Desa', routePath: '/wisata', defaultTitle: 'Wisata Desa', defaultSubtitle: 'Jelajahi keindahan alam dan pesona destinasi wisata desa.' },
    { key: 'services', label: 'Layanan Administrasi', routePath: '/layanan', defaultTitle: 'Layanan Desa', defaultSubtitle: 'Informasi prosedur dan persyaratan layanan administrasi desa.' },
    { key: 'faq', label: 'Tanya Jawab (FAQ)', routePath: '/faq', defaultTitle: 'Tanya Jawab (FAQ)', defaultSubtitle: 'Pertanyaan yang sering diajukan seputar informasi dan pelayanan desa.' },
    { key: 'contact', label: 'Kontak & Lokasi', routePath: '/kontak', defaultTitle: 'Hubungi Kami', defaultSubtitle: 'Layanan informasi dan saluran komunikasi resmi pemerintah desa.' },
];

// Initialize banner data from settings
pageBannerDefinitions.forEach((def) => {
    const rawVal = initialData[`page_banners.${def.key}`];
    let parsed: BannerItemConfig = {
        title: def.defaultTitle,
        subtitle: def.defaultSubtitle,
        image_url: null,
    };

    if (rawVal) {
        try {
            const data = typeof rawVal === 'string' ? JSON.parse(rawVal) : rawVal;
            if (data && typeof data === 'object') {
                parsed = {
                    title: data.title || def.defaultTitle,
                    subtitle: data.subtitle || def.defaultSubtitle,
                    image_url: data.image_url || null,
                    image_path: data.image_path || null,
                };
            }
        } catch {
            // fallback
        }
    }

    bannerConfigs.value[def.key] = parsed;
    bannerFiles.value[def.key] = null;
    bannerPreviews.value[def.key] = parsed.image_url || null;
});

const handleBannerFileSelect = (key: string, file: File) => {
    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!validTypes.includes(file.type)) {
        alert('Format file harus berupa gambar (JPG, PNG, atau WEBP).');
        return;
    }
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran file maksimal 5 MB.');
        return;
    }
    bannerFiles.value[key] = file;
    bannerPreviews.value[key] = URL.createObjectURL(file);
};

const handleBannerDrop = (key: string, e: DragEvent) => {
    if (e.dataTransfer?.files && e.dataTransfer.files[0]) {
        handleBannerFileSelect(key, e.dataTransfer.files[0]);
    }
};

const removeBannerImage = (key: string) => {
    bannerFiles.value[key] = null;
    bannerPreviews.value[key] = null;
    if (bannerConfigs.value[key]) {
        bannerConfigs.value[key].image_url = null;
        bannerConfigs.value[key].image_path = null;
    }
};

const form = useForm({
    settings: initialData,
    banner_files: {} as Record<string, File | null>,
    _method: 'PUT',
});

const activeTab = ref('page_banners');

const submit = () => {
    // Synchronize page_banners JSON
    Object.entries(bannerConfigs.value).forEach(([key, cfg]) => {
        form.settings[`page_banners.${key}`] = JSON.stringify({
            title: cfg.title,
            subtitle: cfg.subtitle,
            image_url: bannerPreviews.value[key] && !bannerFiles.value[key] ? cfg.image_url : null,
            image_path: bannerPreviews.value[key] && !bannerFiles.value[key] ? cfg.image_path : null,
        });
    });

    form.transform((data) => ({
        ...data,
        banner_files: bannerFiles.value,
        _method: 'PUT',
    })).post(route('admin.settings.update'), {
        preserveScroll: true,
        forceFormData: true,
    });
};

const getTabLabel = (group: string) => {
    const labels: Record<string, string> = {
        page_banners: 'Banner Halaman',
        general: 'Umum',
        seo: 'SEO & Metadata',
        appearance: 'Tampilan',
        social: 'Sosial Media',
        maintenance: 'Sistem & Pemeliharaan',
        email: 'Email (SMTP)',
    };

    return labels[group] || group.charAt(0).toUpperCase() + group.slice(1);
};
</script>

<template>
    <Head title="Pengaturan Sistem & Banner" />

    <AdminLayout>
        <div class="space-y-6">
            <PageTitle 
                title="Pengaturan Sistem & Banner" 
                description="Kelola banner latar belakang halaman publik, parameter umum, dan konfigurasi Sistem Informasi Desa."
            />

            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Tabs Navigation -->
                <div class="w-full lg:w-64 shrink-0">
                    <nav class="flex lg:flex-col space-x-2 lg:space-x-0 lg:space-y-1 overflow-x-auto lg:overflow-visible pb-2 lg:pb-0">
                        <!-- Custom Banner Tab First for High Visibility -->
                        <button
                            type="button"
                            @click="activeTab = 'page_banners'"
                            :class="[
                                'px-3.5 py-2.5 text-sm font-semibold rounded-lg text-left whitespace-nowrap transition-colors flex items-center gap-2',
                                activeTab === 'page_banners' 
                                    ? 'bg-emerald-600 text-white shadow-sm' 
                                    : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'
                            ]"
                        >
                            <ImageIcon class="w-4 h-4" />
                            <span>Banner Halaman</span>
                        </button>

                        <template v-for="(groupSettings, groupName) in settings" :key="groupName">
                            <button
                                v-if="groupName !== 'page_banners'"
                                type="button"
                                @click="activeTab = groupName.toString()"
                                :class="[
                                    'px-3.5 py-2.5 text-sm font-medium rounded-lg text-left whitespace-nowrap transition-colors',
                                    activeTab === groupName 
                                        ? 'bg-emerald-600 text-white shadow-sm' 
                                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white'
                                ]"
                            >
                                {{ getTabLabel(groupName.toString()) }}
                            </button>
                        </template>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="flex-1">
                    <form @submit.prevent="submit" class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden shadow-sm">
                        
                        <!-- 1. Dedicated PAGE BANNERS Tab -->
                        <div v-show="activeTab === 'page_banners'" class="p-6 space-y-8">
                            <div class="border-b border-gray-100 dark:border-gray-800 pb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                    <ImageIcon class="w-5 h-5 text-emerald-600" />
                                    Pengaturan Banner & Header Halaman Publik
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Unggah gambar latar belakang (*background banner*), sesuaikan judul, dan subjudul untuk setiap menu publik desa.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div 
                                    v-for="def in pageBannerDefinitions" 
                                    :key="def.key"
                                    class="rounded-xl border border-gray-200 dark:border-gray-800 p-5 bg-gray-50/50 dark:bg-gray-800/40 space-y-4 flex flex-col justify-between hover:border-emerald-500/50 transition-colors"
                                >
                                    <div class="space-y-3">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                {{ def.label }}
                                            </span>
                                            <span class="text-[11px] font-mono px-2 py-0.5 rounded bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ def.routePath }}
                                            </span>
                                        </div>

                                        <!-- Title -->
                                        <div class="space-y-1">
                                            <Label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Judul Header</Label>
                                            <Input 
                                                v-if="bannerConfigs[def.key]"
                                                v-model="bannerConfigs[def.key].title"
                                                type="text"
                                                :placeholder="def.defaultTitle"
                                                class="h-9 text-xs"
                                            />
                                        </div>

                                        <!-- Subtitle -->
                                        <div class="space-y-1">
                                            <Label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Subjudul Header</Label>
                                            <Textarea 
                                                v-if="bannerConfigs[def.key]"
                                                v-model="bannerConfigs[def.key].subtitle"
                                                rows="2"
                                                :placeholder="def.defaultSubtitle"
                                                class="text-xs resize-none"
                                            />
                                        </div>

                                        <!-- Banner Image Upload & Preview -->
                                        <div class="space-y-1.5 pt-1">
                                            <Label class="text-xs font-semibold text-gray-700 dark:text-gray-300">Gambar Latar Belakang (*Banner Image*)</Label>
                                            
                                            <!-- Preview Box -->
                                            <div 
                                                v-if="bannerPreviews[def.key]" 
                                                class="relative w-full aspect-[16/6] rounded-lg overflow-hidden bg-gray-900 border border-gray-200 dark:border-gray-700 group"
                                            >
                                                <img 
                                                    :src="bannerPreviews[def.key]!" 
                                                    alt="Preview Banner" 
                                                    class="w-full h-full object-cover"
                                                />
                                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">
                                                    <label class="cursor-pointer bg-white/90 text-gray-800 hover:bg-white text-xs font-semibold px-2.5 py-1.5 rounded shadow">
                                                        Ganti
                                                        <input 
                                                            type="file" 
                                                            accept="image/*" 
                                                            class="hidden" 
                                                            @change="(e: any) => e.target?.files?.[0] && handleBannerFileSelect(def.key, e.target.files[0])" 
                                                        />
                                                    </label>
                                                    <button 
                                                        type="button" 
                                                        @click="removeBannerImage(def.key)" 
                                                        class="bg-red-600 hover:bg-red-700 text-white text-xs font-semibold px-2.5 py-1.5 rounded shadow flex items-center gap-1"
                                                    >
                                                        <Trash2 class="w-3.5 h-3.5" />
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Dropzone if empty -->
                                            <label 
                                                v-else
                                                class="border border-dashed border-gray-300 dark:border-gray-700 hover:border-emerald-500 rounded-lg p-4 flex flex-col items-center justify-center cursor-pointer bg-white dark:bg-gray-800/50 hover:bg-emerald-50/30 transition-colors"
                                                @dragover.prevent
                                                @drop.prevent="(e) => handleBannerDrop(def.key, e)"
                                            >
                                                <UploadCloud class="w-5 h-5 text-gray-400 mb-1" />
                                                <span class="text-xs text-gray-600 dark:text-gray-300 font-medium">Upload Gambar Banner</span>
                                                <span class="text-[10px] text-gray-400">JPG, PNG, WEBP (Maks 5 MB)</span>
                                                <input 
                                                    type="file" 
                                                    accept="image/*" 
                                                    class="hidden" 
                                                    @change="(e: any) => e.target?.files?.[0] && handleBannerFileSelect(def.key, e.target.files[0])" 
                                                />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Standard Settings Groups (General, SEO, Appearance, etc.) -->
                        <template v-for="(groupSettings, groupName) in settings" :key="`content-${groupName}`">
                            <div 
                                v-if="groupName !== 'page_banners'"
                                v-show="activeTab === groupName.toString()"
                                class="p-6 space-y-8"
                            >
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ getTabLabel(groupName.toString()) }}</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        Kelola pengaturan {{ getTabLabel(groupName.toString()).toLowerCase() }} sistem.
                                    </p>
                                </div>

                                <div class="space-y-6">
                                    <template v-for="setting in groupSettings" :key="setting.key">
                                        <div v-if="setting.is_editable" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start border-t border-gray-100 dark:border-gray-800 pt-4 first:border-0 first:pt-0">
                                            <div class="md:col-span-1 pt-1">
                                                <label :for="setting.key" class="block text-sm font-semibold text-gray-800 dark:text-gray-200 leading-tight">
                                                    {{ setting.label }}
                                                </label>
                                                <p v-if="setting.description" class="text-xs text-gray-400 mt-1">
                                                    {{ setting.description }}
                                                </p>
                                            </div>
                                            
                                            <div class="md:col-span-3">
                                                <!-- Boolean Type (Checkbox/Toggle) -->
                                                <div v-if="setting.type === 'boolean'" class="flex items-center h-10">
                                                    <label class="relative inline-flex items-center cursor-pointer">
                                                        <input 
                                                            type="checkbox" 
                                                            :id="setting.key"
                                                            v-model="form.settings[setting.key]"
                                                            :true-value="'1'"
                                                            :false-value="'0'"
                                                            class="sr-only peer"
                                                        >
                                                        <div class="w-11 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                                                    </label>
                                                </div>

                                                <!-- Textarea Type -->
                                                <Textarea
                                                    v-else-if="setting.type === 'text' || setting.type === 'json'"
                                                    :id="setting.key"
                                                    v-model="form.settings[setting.key]"
                                                    rows="4"
                                                    class="w-full font-mono text-sm"
                                                />

                                                <!-- Select Type -->
                                                <select
                                                    v-else-if="setting.options"
                                                    :id="setting.key"
                                                    v-model="form.settings[setting.key]"
                                                    class="flex h-10 w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-emerald-500"
                                                >
                                                    <option v-for="(label, val) in setting.options" :key="val" :value="val">
                                                        {{ label }}
                                                    </option>
                                                </select>

                                                <!-- Default Input (String, Integer) -->
                                                <Input
                                                    v-else
                                                    :id="setting.key"
                                                    v-model="form.settings[setting.key]"
                                                    :type="setting.type === 'integer' ? 'number' : (setting.is_encrypted ? 'password' : 'text')"
                                                    class="w-full h-10"
                                                />
                                                
                                                <div v-if="form.errors[`settings.${setting.key}`]" class="text-xs text-red-500 mt-1">
                                                    {{ form.errors[`settings.${setting.key}`] }}
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Submit Footer -->
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/40 flex items-center justify-end">
                            <Button 
                                type="submit" 
                                class="bg-emerald-600 hover:bg-emerald-700 text-white font-semibold flex items-center gap-2 shadow-sm"
                                :disabled="form.processing"
                            >
                                <Icons.SaveIcon class="w-4 h-4" />
                                <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Semua Pengaturan' }}</span>
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
