<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { 
    AlertCircle, 
    ArrowRight, 
    Briefcase, 
    Calendar, 
    ChevronRight, 
    FileText, 
    Home, 
    Image as ImageIcon, 
    Mail, 
    MapPin, 
    Phone, 
    Sparkles, 
    Store, 
    Users 
} from 'lucide-vue-next';
import HeroSection from '@/components/public/HeroSection.vue';
import SectionHeader from '@/components/public/SectionHeader.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

interface HomepageSection {
    ulid: string;
    section_key: string;
    title: string | null;
    subtitle: string | null;
    content: string | null;
    configuration: any;
    aggregated_data: any;
}

const props = defineProps<{
    sections: HomepageSection[];
}>();

const page = usePage();
const village = page.props.village as any;

const formatDate = (dateString?: string | null) => {
    if (!dateString) return '-';
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(dateString));
};

const stripHtml = (html?: string | null): string => {
    if (!html) return '';
    const formatted = html.replace(/<br\s*[\/]?>/gi, ' ').replace(/<\/p>/gi, ' ');
    const text = formatted.replace(/<[^>]*>/g, '');
    return text.replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/\s+/g, ' ').trim();
};

const getGalleryPhoto = (item: any): string | null => {
    if (item.featured_image_url) return item.featured_image_url;
    if (item.featured_image_path) {
        return item.featured_image_path.startsWith('http') ? item.featured_image_path : `/storage/${item.featured_image_path.replace(/^\//, '')}`;
    }
    const photo = item.photos?.[0];
    if (!photo) return null;
    if (photo.image_url) return photo.image_url;
    if (photo.image_path) {
        return photo.image_path.startsWith('http') ? photo.image_path : `/storage/${photo.image_path.replace(/^\//, '')}`;
    }
    if (photo.file_path) {
        return photo.file_path.startsWith('http') ? photo.file_path : `/storage/${photo.file_path.replace(/^\//, '')}`;
    }
    return null;
};
</script>

<template>
    <PublicLayout>
        <Head title="Beranda Resmi Desa Burujuljaya" />

        <!-- Fallback if sections empty -->
        <template v-if="!sections || sections.length === 0">
            <HeroSection />
            <section class="py-16 max-w-7xl mx-auto px-4 text-center">
                <div class="p-8 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm max-w-xl mx-auto space-y-4">
                    <Sparkles class="w-12 h-12 text-emerald-600 mx-auto" />
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">Selamat Datang di Portal Desa Burujuljaya</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Pusat informasi publik dan layanan masyarakat Desa Burujuljaya.
                    </p>
                </div>
            </section>
        </template>

        <!-- Dynamic Sections Iterator -->
        <template v-else>
            <template v-for="section in sections" :key="section.ulid">
                
                <!-- 1. Hero Slider Section -->
                <template v-if="section.section_key === 'hero_slider'">
                    <HeroSection 
                        :bannerImage="section.aggregated_data?.[0]?.image_url" 
                        :title="section.title || undefined"
                        :subtitle="section.subtitle || undefined"
                    />
                </template>

                <!-- 2. Welcome Message / Sambutan Kepala Desa -->
                <section v-else-if="section.section_key === 'welcome_message'" class="py-16 bg-emerald-50/50 dark:bg-emerald-950/20 border-y border-emerald-100 dark:border-emerald-900/30">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
                            <!-- Left: Profile Logo -->
                            <div class="lg:col-span-4 flex justify-center">
                                <div class="relative group">
                                    <div class="absolute -inset-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl blur opacity-30 group-hover:opacity-60 transition duration-500"></div>
                                    <div class="relative w-64 h-72 sm:w-72 sm:h-80 rounded-2xl bg-white dark:bg-gray-900 border border-emerald-100 dark:border-emerald-800/60 p-4 shadow-xl flex flex-col items-center justify-center text-center overflow-hidden">
                                        <img 
                                            v-if="village?.logo_url" 
                                            :src="village.logo_url" 
                                            alt="Logo Desa" 
                                            class="w-32 h-32 object-contain mb-4"
                                        />
                                        <div v-else class="w-28 h-28 rounded-full bg-emerald-100 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-300 font-extrabold text-3xl flex items-center justify-center mb-3">
                                            DB
                                        </div>
                                        <h4 class="font-bold text-gray-900 dark:text-white text-base">Pemerintah Desa</h4>
                                        <p class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold">{{ village?.village_name || 'Burujuljaya' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Message Content -->
                            <div class="lg:col-span-8 space-y-5">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-900/50 text-emerald-800 dark:text-emerald-300 text-xs font-semibold">
                                    <span>Visi & Komitmen Desa</span>
                                </div>
                                <h2 class="text-2xl sm:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                                    {{ section.title || 'Membangun Desa Bersama Warga' }}
                                </h2>
                                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-300 leading-relaxed">
                                    {{ section.subtitle || village?.vision || 'Kami berkomitmen untuk memberikan pelayanan publik yang transparan, profesional, dan akuntabel demi kemajuan dan kesejahteraan seluruh masyarakat Desa Burujuljaya.' }}
                                </p>
                                <div class="pt-2 flex flex-wrap gap-4">
                                    <Link :href="route('profile.index')" class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300">
                                        <span>Baca Selengkapnya tentang Desa</span>
                                        <ChevronRight class="w-4 h-4" />
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 3. Statistics Section -->
                <section v-else-if="section.section_key === 'statistics'" class="py-14 bg-white dark:bg-gray-900">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <SectionHeader 
                            :title="section.title || 'Statistik & Demografi Desa'" 
                            :subtitle="section.subtitle || 'Data ringkas kependudukan dan luas wilayah Desa Burujuljaya'"
                            align="center"
                        />
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                            <div class="bg-gray-50 dark:bg-gray-800/80 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 text-center hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors shadow-sm">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-emerald-100 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 mb-3">
                                    <Users class="w-6 h-6" />
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ section.aggregated_data?.population || '3.420' }}</div>
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider">Total Penduduk</div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/80 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 text-center hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors shadow-sm">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-teal-100 dark:bg-teal-950/60 text-teal-700 dark:text-teal-400 mb-3">
                                    <Home class="w-6 h-6" />
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ section.aggregated_data?.families || '1.045' }}</div>
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider">Kepala Keluarga</div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/80 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 text-center hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors shadow-sm">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-amber-100 dark:bg-amber-950/60 text-amber-700 dark:text-amber-400 mb-3">
                                    <MapPin class="w-6 h-6" />
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                                    {{ section.aggregated_data?.area || '14.5' }} <span class="text-sm font-normal text-gray-500">Ha</span>
                                </div>
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider">Luas Wilayah</div>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-800/80 p-6 rounded-2xl border border-gray-100 dark:border-gray-800 text-center hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors shadow-sm">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-100 dark:bg-purple-950/60 text-purple-700 dark:text-purple-400 mb-3">
                                    <Briefcase class="w-6 h-6" />
                                </div>
                                <div class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">{{ section.aggregated_data?.rt_rw || '18 / 06' }}</div>
                                <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1 uppercase tracking-wider">Jumlah RT / RW</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 4. Featured Articles / Berita Terkini -->
                <section v-else-if="section.section_key === 'featured_articles'" class="py-16 bg-gray-50 dark:bg-gray-950/40">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                            <SectionHeader 
                                :title="section.title || 'Kabar & Berita Desa'" 
                                :subtitle="section.subtitle || 'Informasi terbaru seputar kegiatan dan perkembangan di Desa Burujuljaya'" 
                                align="left" 
                                class="mb-0" 
                            />
                            <Link 
                                :href="route('articles.index')" 
                                class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 self-start sm:self-auto"
                            >
                                <span>Lihat Semua Berita</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>

                        <div v-if="section.aggregated_data && section.aggregated_data.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                            <article 
                                v-for="article in section.aggregated_data" 
                                :key="article.id" 
                                class="bg-white dark:bg-gray-900 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300 group"
                            >
                                <div class="h-48 relative overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    <img 
                                        v-if="article.featured_image_path" 
                                        :src="'/storage/' + article.featured_image_path" 
                                        :alt="article.title" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                        <ImageIcon class="w-10 h-10 stroke-1" />
                                    </div>
                                </div>
                                <div class="p-6 flex flex-col flex-1">
                                    <div class="flex items-center gap-2 text-xs text-gray-400 mb-2.5">
                                        <Calendar class="w-3.5 h-3.5" />
                                        <span>{{ formatDate(article.published_at) }}</span>
                                    </div>
                                    <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                        <Link :href="route('articles.show', article.slug)">
                                            {{ article.title }}
                                        </Link>
                                    </h3>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4 flex-1">
                                        {{ stripHtml(article.excerpt || article.content || article.title) }}
                                    </p>
                                    <Link 
                                        :href="route('articles.show', article.slug)" 
                                        class="inline-flex items-center gap-1 text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:text-emerald-700"
                                    >
                                        <span>Baca Artikel</span>
                                        <ChevronRight class="w-3.5 h-3.5" />
                                    </Link>
                                </div>
                            </article>
                        </div>
                        <div v-else class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 text-center text-gray-400">
                            <FileText class="w-10 h-10 mx-auto mb-2 stroke-1 text-gray-300 dark:text-gray-600" />
                            <p class="text-sm font-medium">Belum ada artikel berita yang dipublikasikan.</p>
                        </div>
                    </div>
                </section>

                <!-- 5. UMKM Desa -->
                <section v-else-if="section.section_key === 'umkm'" class="py-16 bg-white dark:bg-gray-900">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                            <SectionHeader 
                                :title="section.title || 'Sentra UMKM Desa'" 
                                :subtitle="section.subtitle || 'Dukung usaha mikro dan produk kreatif karya warga Desa Burujuljaya'" 
                                align="left" 
                                class="mb-0" 
                            />
                            <Link 
                                :href="route('umkm.index')" 
                                class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 self-start sm:self-auto"
                            >
                                <span>Katalog UMKM</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>

                        <div v-if="section.aggregated_data && section.aggregated_data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="umkm in section.aggregated_data" 
                                :key="umkm.id" 
                                class="bg-gray-50 dark:bg-gray-800/60 rounded-2xl border border-gray-200/80 dark:border-gray-800 p-5 hover:shadow-md transition-all duration-300 group flex flex-col"
                            >
                                <div class="h-40 rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-700 relative mb-4">
                                    <img 
                                        v-if="umkm.featured_image_path" 
                                        :src="'/storage/' + umkm.featured_image_path" 
                                        :alt="umkm.title" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                        <Store class="w-10 h-10 stroke-1" />
                                    </div>
                                </div>
                                <h3 class="font-bold text-gray-900 dark:text-white text-base group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                    <Link :href="route('umkm.show', umkm.slug)">
                                        {{ umkm.title }}
                                    </Link>
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-3 line-clamp-2 flex-1">
                                    {{ stripHtml(umkm.description) || 'Pelaku UMKM lokal Desa Burujuljaya.' }}
                                </p>
                                <Link 
                                    :href="route('umkm.show', umkm.slug)" 
                                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 inline-flex items-center gap-1 mt-auto"
                                >
                                    <span>Lihat Detail Usaha</span>
                                    <ChevronRight class="w-3 h-3" />
                                </Link>
                            </div>
                        </div>
                        <div v-else class="bg-gray-50 dark:bg-gray-800/40 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 text-center text-gray-400">
                            <Store class="w-10 h-10 mx-auto mb-2 stroke-1 text-gray-300 dark:text-gray-600" />
                            <p class="text-sm font-medium">Belum ada data UMKM desa.</p>
                        </div>
                    </div>
                </section>

                <!-- 6. Gallery Section -->
                <section v-else-if="section.section_key === 'gallery'" class="py-16 bg-gray-50 dark:bg-gray-950/40">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-10 gap-4">
                            <SectionHeader 
                                :title="section.title || 'Galeri Dokumentasi'" 
                                :subtitle="section.subtitle || 'Potret kegiatan masyarakat dan pembangunan Desa Burujuljaya'" 
                                align="left" 
                                class="mb-0" 
                            />
                            <Link 
                                :href="route('gallery.index')" 
                                class="inline-flex items-center gap-1.5 text-sm font-semibold text-emerald-700 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 self-start sm:self-auto"
                            >
                                <span>Lihat Semua Foto</span>
                                <ArrowRight class="w-4 h-4" />
                            </Link>
                        </div>

                        <div v-if="section.aggregated_data && section.aggregated_data.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div 
                                v-for="item in section.aggregated_data" 
                                :key="item.id" 
                                class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 overflow-hidden group shadow-sm"
                            >
                                <div class="h-48 relative overflow-hidden bg-gray-100 dark:bg-gray-800">
                                    <img 
                                        v-if="getGalleryPhoto(item)" 
                                        :src="getGalleryPhoto(item)!" 
                                        :alt="item.title" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
                                    />
                                    <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                        <ImageIcon class="w-10 h-10 stroke-1" />
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-900 dark:text-white text-sm line-clamp-1 group-hover:text-emerald-600 transition-colors">
                                        <Link :href="route('gallery.show', item.slug)">{{ item.title }}</Link>
                                    </h4>
                                    <p class="text-xs text-gray-400 mt-1">{{ formatDate(item.published_at) }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-8 text-center text-gray-400">
                            <ImageIcon class="w-10 h-10 mx-auto mb-2 stroke-1 text-gray-300 dark:text-gray-600" />
                            <p class="text-sm font-medium">Belum ada galeri dokumentasi kegiatan.</p>
                        </div>
                    </div>
                </section>

                <!-- 7. Map / Lokasi Desa Section -->
                <section v-else-if="section.section_key === 'map'" class="py-16 bg-white dark:bg-gray-900 border-t border-gray-100 dark:border-gray-800">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <SectionHeader 
                            :title="section.title || 'Lokasi & Peta Desa'" 
                            :subtitle="section.subtitle || 'Temukan lokasi kantor balai desa dan wilayah administratif kami'" 
                            align="center" 
                            class="mb-10" 
                        />

                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                            <!-- Info Card -->
                            <div class="lg:col-span-4 bg-gray-50 dark:bg-gray-800/60 rounded-3xl p-6 sm:p-8 border border-gray-200 dark:border-gray-700 flex flex-col justify-between space-y-6">
                                <div class="space-y-4">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-400 text-xs font-semibold border border-emerald-200 dark:border-emerald-800">
                                        <MapPin class="w-3.5 h-3.5" />
                                        <span>Pusat Pemerintahan Desa</span>
                                    </div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                                        Kantor Desa {{ section.aggregated_data?.village_name || village?.name || 'Burujuljaya' }}
                                    </h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">
                                        {{ section.aggregated_data?.address || village?.address || 'Jl. Raya Desa Burujuljaya' }}
                                    </p>
                                    
                                    <div class="pt-2 space-y-2.5 text-xs text-gray-500 dark:text-gray-400">
                                        <div v-if="section.aggregated_data?.phone || village?.phone" class="flex items-center gap-2">
                                            <Phone class="w-4 h-4 text-emerald-600" />
                                            <span>{{ section.aggregated_data?.phone || village?.phone }}</span>
                                        </div>
                                        <div v-if="section.aggregated_data?.email || village?.email" class="flex items-center gap-2">
                                            <Mail class="w-4 h-4 text-emerald-600" />
                                            <span>{{ section.aggregated_data?.email || village?.email }}</span>
                                        </div>
                                    </div>
                                </div>

                                <a 
                                    :href="`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent((section.aggregated_data?.address ? section.aggregated_data.address + ' ' : '') + 'Desa ' + (section.aggregated_data?.village_name || village?.name || 'Burujuljaya'))}`" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold shadow-sm transition-all"
                                >
                                    <MapPin class="w-4 h-4" />
                                    <span>Buka di Google Maps</span>
                                    <ArrowRight class="w-4 h-4" />
                                </a>
                            </div>

                            <!-- Map Iframe Box -->
                            <div class="lg:col-span-8 rounded-3xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm bg-gray-100 dark:bg-gray-800 min-h-[420px] h-full flex">
                                <iframe 
                                    v-if="section.aggregated_data?.embed_url"
                                    :src="section.aggregated_data.embed_url" 
                                    class="w-full h-full min-h-[420px] border-0 block flex-1" 
                                    allowfullscreen 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                                <iframe 
                                    v-else
                                    :src="`https://maps.google.com/maps?q=${encodeURIComponent((section.aggregated_data?.latitude && section.aggregated_data?.longitude ? `${section.aggregated_data.latitude},${section.aggregated_data.longitude}` : (section.aggregated_data?.address || 'Desa Burujuljaya')))}&t=&z=14&ie=UTF8&iwloc=&output=embed`"
                                    class="w-full h-full min-h-[420px] border-0 block flex-1" 
                                    allowfullscreen 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- 8. Default / Generic Section Content -->
                <section v-else-if="section.content" class="py-16 bg-gray-50 dark:bg-gray-900">
                    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <SectionHeader :title="section.title || ''" :subtitle="section.subtitle || ''" align="center" />
                        <div class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-200 dark:border-gray-800 prose dark:prose-invert max-w-none mx-auto" v-html="section.content"></div>
                    </div>
                </section>

            </template>
        </template>
    </PublicLayout>
</template>
