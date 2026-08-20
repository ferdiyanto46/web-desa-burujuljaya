<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    ArrowDown, 
    ArrowUp, 
    Check, 
    Eye, 
    Layers, 
    Loader2, 
    RefreshCw, 
    Settings2, 
    SlidersHorizontal 
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface HomepageSection {
    id: number;
    ulid: string;
    section_key: string;
    title: string | null;
    subtitle: string | null;
    sort_order: number;
    is_enabled: boolean;
    status: string;
}

const props = defineProps<{
    sections: HomepageSection[];
}>();

const isReordering = ref(false);
const isSaving = ref(false);
const localSections = ref<HomepageSection[]>([...props.sections]);

watch(() => props.sections, (newSections) => {
    localSections.value = [...newSections];
});

const getSectionLabel = (key: string): { label: string; desc: string } => {
    switch (key) {
        case 'hero_slider':
            return { label: 'Hero Banner & Slider', desc: 'Slide gambar dan sambutan utama di bagian paling atas beranda' };
        case 'announcements':
            return { label: 'Pengumuman Penting', desc: 'Warta kilat dan informasi darurat untuk masyarakat desa' };
        case 'featured_articles':
            return { label: 'Berita & Artikel Terkini', desc: 'Daftar berita, artikel, dan kabar seputar desa' };
        case 'village_services':
            return { label: 'Layanan & Administrasi Desa', desc: 'Pintasan pengajuan surat dan layanan publik warga' };
        case 'tourism':
            return { label: 'Potensi Wisata', desc: 'Destinasi wisata dan keindahan alam di Desa Burujuljaya' };
        case 'umkm':
            return { label: 'UMKM & Usaha Warga', desc: 'Daftar pelaku usaha mikro dan sentra bisnis desa' };
        case 'village_products':
            return { label: 'Produk Unggulan Desa', desc: 'Katalog produk lokal dan komoditas unggulan desa' };
        case 'gallery':
            return { label: 'Galeri Foto Kegiatan', desc: 'Dokumentasi visual kegiatan kemasyarakatan dan pembangunan' };
        case 'statistics':
            return { label: 'Statistik & Infografis', desc: 'Ringkasan data penduduk, luas wilayah, dan anggaran' };
        case 'faq':
            return { label: 'Tanya Jawab (FAQ)', desc: 'Pertanyaan umum seputar administrasi dan info desa' };
        case 'contact_preview':
            return { label: 'Kontak & Lokasi Kantor', desc: 'Peta lokasi kantor desa, kontak darurat, dan jam kerja' };
        default:
            return { label: key, desc: 'Komponen bagian beranda' };
    }
};

const moveUp = (index: number) => {
    if (index > 0) {
        const temp = localSections.value[index - 1];
        localSections.value[index - 1] = localSections.value[index];
        localSections.value[index] = temp;
        isReordering.value = true;
    }
};

const moveDown = (index: number) => {
    if (index < localSections.value.length - 1) {
        const temp = localSections.value[index + 1];
        localSections.value[index + 1] = localSections.value[index];
        localSections.value[index] = temp;
        isReordering.value = true;
    }
};

const saveOrder = () => {
    isSaving.value = true;
    const orderData: Record<string, number> = {};
    localSections.value.forEach((section, index) => {
        orderData[section.ulid] = index;
    });

    router.post(route('admin.homepage-sections.reorder'), { order: orderData }, {
        preserveScroll: true,
        onSuccess: () => {
            isReordering.value = false;
        },
        onFinish: () => {
            isSaving.value = false;
        },
    });
};

const toggleStatus = (section: HomepageSection) => {
    router.patch(route('admin.homepage-sections.toggle', section.ulid), {}, {
        preserveScroll: true,
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Tata Letak Beranda" />

        <div class="space-y-6 max-w-7xl mx-auto pb-12">
            <!-- Header Section -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <PageTitle 
                    title="Tata Letak Beranda" 
                    subtitle="Atur susunan urutan, visibilitas (tampilkan/sembunyikan), dan konfigurasi seksi halaman utama website desa" 
                />

                <div class="flex items-center gap-2">
                    <Button 
                        v-if="isReordering"
                        @click="saveOrder" 
                        variant="default"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-2"
                        :disabled="isSaving"
                    >
                        <Loader2 v-if="isSaving" class="w-4 h-4 animate-spin" />
                        <Check v-else class="w-4 h-4" />
                        <span>{{ isSaving ? 'Menyimpan...' : 'Simpan Urutan Baru' }}</span>
                    </Button>

                    <a 
                        :href="route('home')" 
                        target="_blank"
                    >
                        <Button 
                            type="button"
                            variant="outline"
                            class="flex items-center gap-2 text-gray-700 dark:text-gray-300"
                        >
                            <Eye class="w-4 h-4 text-emerald-600" />
                            <span>Pratinjau Beranda</span>
                        </Button>
                    </a>
                </div>
            </div>

            <!-- Notice / Banner when reordering -->
            <div 
                v-if="isReordering" 
                class="bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex items-center justify-between gap-4"
            >
                <div class="flex items-center gap-3 text-amber-800 dark:text-amber-300 text-sm">
                    <RefreshCw class="w-5 h-5 animate-spin text-amber-600" />
                    <span>Anda telah mengubah urutan susunan beranda. Jangan lupa klik <strong>"Simpan Urutan Baru"</strong> agar perubahan diterapkan.</span>
                </div>
                <Button 
                    size="sm" 
                    class="bg-amber-600 hover:bg-amber-700 text-white text-xs shrink-0"
                    :disabled="isSaving"
                    @click="saveOrder"
                >
                    Simpan Sekarang
                </Button>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-800 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 w-28 text-center">Urutan</th>
                                <th scope="col" class="py-3.5 px-4">Bagian Seksi (Section)</th>
                                <th scope="col" class="py-3.5 px-4">Judul Kustom Beranda</th>
                                <th scope="col" class="py-3.5 px-4 text-center">Status Tayang</th>
                                <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                            <tr 
                                v-for="(section, index) in localSections" 
                                :key="section.ulid"
                                class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors"
                            >
                                <!-- Sort Actions -->
                                <td class="py-4 px-4 text-center">
                                    <div class="inline-flex items-center gap-1 bg-gray-100 dark:bg-gray-800 p-1 rounded-lg">
                                        <button 
                                            type="button" 
                                            class="p-1 rounded text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 disabled:opacity-30 disabled:hover:bg-transparent"
                                            :disabled="index === 0"
                                            title="Pindah ke Atas"
                                            @click="moveUp(index)"
                                        >
                                            <ArrowUp class="w-3.5 h-3.5" />
                                        </button>
                                        <span class="text-xs font-bold w-5 text-center text-gray-700 dark:text-gray-300">{{ index + 1 }}</span>
                                        <button 
                                            type="button" 
                                            class="p-1 rounded text-gray-600 dark:text-gray-400 hover:bg-white dark:hover:bg-gray-700 disabled:opacity-30 disabled:hover:bg-transparent"
                                            :disabled="index === localSections.length - 1"
                                            title="Pindah ke Bawah"
                                            @click="moveDown(index)"
                                        >
                                            <ArrowDown class="w-3.5 h-3.5" />
                                        </button>
                                    </div>
                                </td>

                                <!-- Section Info -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-lg bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex items-center justify-center text-emerald-700 dark:text-emerald-400 shrink-0">
                                            <Layers class="w-4 h-4" />
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                                {{ getSectionLabel(section.section_key).label }}
                                            </p>
                                            <span class="text-xs text-gray-400">
                                                {{ getSectionLabel(section.section_key).desc }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Title & Subtitle -->
                                <td class="py-4 px-4">
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-gray-100 text-sm">
                                            {{ section.title || '-' }}
                                        </span>
                                        <span class="text-xs text-gray-400 truncate max-w-xs">
                                            {{ section.subtitle || 'Menggunakan deskripsi default' }}
                                        </span>
                                    </div>
                                </td>

                                <!-- Status Toggle -->
                                <td class="py-4 px-4 text-center">
                                    <button 
                                        type="button"
                                        @click="toggleStatus(section)" 
                                        class="inline-flex items-center gap-1.5 focus:outline-none transition-transform hover:scale-105"
                                        title="Klik untuk mengubah status tayang"
                                    >
                                        <Badge 
                                            v-if="section.is_enabled"
                                            variant="outline" 
                                            class="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800"
                                        >
                                            Aktif Tayang
                                        </Badge>
                                        <Badge 
                                            v-else
                                            variant="outline" 
                                            class="bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-400 border-gray-200 dark:border-gray-700"
                                        >
                                            Disembunyikan
                                        </Badge>
                                    </button>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-4 text-right">
                                    <Link :href="route('admin.homepage-sections.edit', section.ulid)">
                                        <Button 
                                            type="button"
                                            variant="ghost" 
                                            size="sm" 
                                            class="h-8 px-3 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 gap-1.5"
                                        >
                                            <Settings2 class="w-4 h-4" />
                                            <span>Konfigurasi</span>
                                        </Button>
                                    </Link>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="localSections.length === 0">
                                <td colspan="5" class="py-12 px-4 text-center text-gray-400">
                                    <SlidersHorizontal class="w-10 h-10 mx-auto stroke-1 text-gray-300 dark:text-gray-600 mb-2" />
                                    <p class="text-sm font-medium">Belum ada konfigurasi bagian beranda.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
