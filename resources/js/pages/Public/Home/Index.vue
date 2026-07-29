<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import EmptySection from '@/components/public/EmptySection.vue';
import HeroSection from '@/components/public/HeroSection.vue';
import SectionHeader from '@/components/public/SectionHeader.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    banners: Array<any>;
    villageProfile: any;
    latestArticles: Array<any>;
    latestAnnouncements: Array<any>;
    upcomingAgendas: Array<any>;
    latestGalleries: Array<any>;
    latestVideo: any;
    featuredUmkms: Array<any>;
    featuredProducts: Array<any>;
    featuredTourisms: Array<any>;
    statistics: any;
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    }).format(new Date(dateString));
};
</script>

<template>
    <Head title="Beranda" />

    <PublicLayout>
        <template #banner>
            <HeroSection :bannerImage="banners?.[0]?.image_url" />
        </template>

        <div class="space-y-24 py-8">
            
            <!-- Welcome Village -->
            <section class="scroll-mt-24">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0 bg-blue-50 dark:bg-gray-900 md:w-1/3 flex items-center justify-center p-8">
                            <img v-if="villageProfile?.logo_url" :src="villageProfile.logo_url" alt="Logo Desa" class="w-32 h-32 object-contain" />
                            <component v-else :is="Icons.MapIcon" class="w-32 h-32 text-blue-200 dark:text-gray-700" />
                        </div>
                        <div class="p-8 md:p-12 flex flex-col justify-center w-full">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Profil Desa Burujuljaya</h2>
                            <div class="text-lg text-gray-600 dark:text-gray-300 mb-6 prose dark:prose-invert max-w-none line-clamp-4" v-html="villageProfile?.description || 'Selamat datang di website resmi Desa Burujuljaya.'"></div>
                            <div class="mt-auto">
                                <Link :href="route('pages.profile')" class="inline-flex items-center text-blue-600 dark:text-blue-400 font-medium hover:text-blue-700 dark:hover:text-blue-300">
                                    Baca Profil Lengkap <component :is="Icons.ChevronRightIcon" class="w-4 h-4 ml-1" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            
            <!-- Statistik Singkat -->
            <section class="scroll-mt-24">
                <SectionHeader 
                    title="Statistik Desa" 
                    subtitle="Data ringkas kependudukan dan kewilayahan."
                    align="center"
                />
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-4">
                            <component :is="Icons.UsersIcon" class="w-6 h-6" />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ statistics?.population || '0' }}</div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Penduduk</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 mb-4">
                            <component :is="Icons.HomeIcon" class="w-6 h-6" />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ statistics?.families || '0' }}</div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Keluarga</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 mb-4">
                            <component :is="Icons.MapIcon" class="w-6 h-6" />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ statistics?.area || '0' }}<span class="text-lg text-gray-500">km&sup2;</span></div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Luas Wilayah</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 text-center">
                        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 mb-4">
                            <component :is="Icons.MoreVerticalIcon" class="w-6 h-6" />
                        </div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ statistics?.rt_rw || '0/0' }}</div>
                        <div class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">RT / RW</div>
                    </div>
                </div>
            </section>

            <!-- Artikel Terbaru -->
            <section class="scroll-mt-24">
                <div class="flex justify-between items-end mb-8">
                    <SectionHeader title="Kabar Desa" subtitle="Informasi dan berita terbaru." align="left" class="mb-0" />
                    <Link :href="route('articles.index')" class="hidden sm:inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">
                        Lihat Semua <component :is="Icons.ChevronRightIcon" class="w-4 h-4 ml-1" />
                    </Link>
                </div>
                <div v-if="latestArticles?.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="article in latestArticles" :key="article.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow group">
                        <div class="h-48 relative overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img v-if="article.cover_image_url" :src="article.cover_image_url" :alt="article.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center"><component :is="Icons.ImageIcon" class="w-12 h-12 text-gray-400" /></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ formatDate(article.published_at) }}</div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors">
                                <Link :href="route('articles.show', article.slug)">{{ article.title }}</Link>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4 flex-grow">{{ article.excerpt }}</p>
                            <Link :href="route('articles.show', article.slug)" class="text-sm font-medium text-blue-600 dark:text-blue-400 mt-auto">Baca selengkapnya &rarr;</Link>
                        </div>
                    </div>
                </div>
                <EmptySection v-else title="Belum Ada Artikel" :icon="Icons.FileTextIcon" />
            </section>

            <!-- Pengumuman & Agenda Grid -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-8 scroll-mt-24">
                <!-- Pengumuman -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <component :is="Icons.MegaphoneIcon" class="w-5 h-5 text-blue-500" />
                            Pengumuman
                        </h3>
                        <Link :href="route('announcements.index')" class="text-sm font-medium text-blue-600 hover:text-blue-500">Lihat Semua</Link>
                    </div>
                    <div v-if="latestAnnouncements?.length > 0" class="space-y-4">
                        <Link v-for="announcement in latestAnnouncements" :key="announcement.id" :href="route('announcements.show', announcement.slug)" class="block p-4 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <h4 class="font-semibold text-gray-900 dark:text-white mb-1">{{ announcement.title }}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ formatDate(announcement.created_at) }}</p>
                        </Link>
                    </div>
                    <p v-else class="text-gray-500 text-sm text-center py-4">Belum ada pengumuman.</p>
                </div>

                <!-- Agenda -->
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                            <component :is="Icons.CalendarIcon" class="w-5 h-5 text-emerald-500" />
                            Agenda Terdekat
                        </h3>
                        <Link :href="route('agendas.index')" class="text-sm font-medium text-emerald-600 hover:text-emerald-500">Lihat Semua</Link>
                    </div>
                    <div v-if="upcomingAgendas?.length > 0" class="space-y-4">
                        <Link v-for="agenda in upcomingAgendas" :key="agenda.id" :href="route('agendas.show', agenda.slug)" class="flex gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-lg p-2 text-center w-16 flex flex-col justify-center">
                                <span class="text-2xl font-bold leading-none">{{ new Date(agenda.start_date).getDate() }}</span>
                                <span class="text-xs uppercase font-medium mt-1">{{ new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(new Date(agenda.start_date)) }}</span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-white mb-1 line-clamp-1">{{ agenda.title }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-1 flex items-center gap-1">
                                    <component :is="Icons.MapIcon" class="w-3 h-3" /> {{ agenda.location }}
                                </p>
                            </div>
                        </Link>
                    </div>
                    <p v-else class="text-gray-500 text-sm text-center py-4">Belum ada agenda terdekat.</p>
                </div>
            </section>
            
            <!-- Wisata, Produk, UMKM Section -->
            <section class="scroll-mt-24">
                <SectionHeader title="Potensi Desa" subtitle="Jelajahi keindahan, produk unggulan, dan usaha lokal Desa Burujuljaya." align="center" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Tourism -->
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Wisata</h3>
                            <Link :href="route('tourism.index')" class="text-sm text-blue-600 hover:underline">Selengkapnya</Link>
                        </div>
                        <div v-if="featuredTourisms?.length > 0" class="space-y-4">
                            <Link v-for="item in featuredTourisms.slice(0, 3)" :key="item.id" :href="route('tourism.show', item.slug)" class="flex gap-4 group">
                                <img v-if="item.thumbnail_url" :src="item.thumbnail_url" :alt="item.name" class="w-20 h-20 rounded-lg object-cover group-hover:opacity-80 transition-opacity" />
                                <div v-else class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400"><component :is="Icons.ImageIcon" class="w-6 h-6" /></div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">{{ item.name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 line-clamp-2 mt-1">{{ item.description }}</p>
                                </div>
                            </Link>
                        </div>
                        <div v-else class="text-sm text-gray-500 italic p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">Belum ada data wisata.</div>
                    </div>
                    
                    <!-- Products -->
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Produk Unggulan</h3>
                            <Link :href="route('products.index')" class="text-sm text-blue-600 hover:underline">Selengkapnya</Link>
                        </div>
                        <div v-if="featuredProducts?.length > 0" class="space-y-4">
                            <Link v-for="item in featuredProducts.slice(0, 3)" :key="item.id" :href="route('products.show', item.slug)" class="flex gap-4 group">
                                <img v-if="item.image_url" :src="item.image_url" :alt="item.name" class="w-20 h-20 rounded-lg object-cover group-hover:opacity-80 transition-opacity" />
                                <div v-else class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400"><component :is="Icons.PackageIcon" class="w-6 h-6" /></div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">{{ item.name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 font-medium">Rp {{ new Intl.NumberFormat('id-ID').format(item.price) }}</p>
                                </div>
                            </Link>
                        </div>
                        <div v-else class="text-sm text-gray-500 italic p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">Belum ada produk.</div>
                    </div>
                    
                    <!-- UMKM -->
                    <div class="flex flex-col">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">UMKM</h3>
                            <Link :href="route('umkm.index')" class="text-sm text-blue-600 hover:underline">Selengkapnya</Link>
                        </div>
                        <div v-if="featuredUmkms?.length > 0" class="space-y-4">
                            <Link v-for="item in featuredUmkms.slice(0, 3)" :key="item.id" :href="route('umkm.show', item.slug)" class="flex gap-4 group">
                                <img v-if="item.logo_url" :src="item.logo_url" :alt="item.name" class="w-20 h-20 rounded-lg object-cover group-hover:opacity-80 transition-opacity" />
                                <div v-else class="w-20 h-20 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-400"><component :is="Icons.StoreIcon" class="w-6 h-6" /></div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-1">{{ item.name }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ item.owner_name }}</p>
                                </div>
                            </Link>
                        </div>
                        <div v-else class="text-sm text-gray-500 italic p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">Belum ada data UMKM.</div>
                    </div>
                </div>
            </section>

            <!-- Video & Gallery Preview -->
            <section v-if="latestVideo || latestGalleries?.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-8 scroll-mt-24">
                <div v-if="latestVideo" class="bg-gray-900 rounded-3xl overflow-hidden shadow-lg relative aspect-video">
                    <iframe 
                        :src="`https://www.youtube.com/embed/${latestVideo.youtube_id}`" 
                        title="YouTube video player" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen
                        class="absolute inset-0 w-full h-full"
                    ></iframe>
                </div>
                <div v-if="latestGalleries?.length > 0" class="bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Galeri Terbaru</h3>
                    <div class="grid grid-cols-2 gap-4 flex-grow">
                        <Link v-for="gallery in latestGalleries" :key="gallery.id" :href="route('gallery.show', gallery.slug)" class="block relative rounded-xl overflow-hidden group h-32">
                            <img v-if="gallery.photos?.[0]?.image_url" :src="gallery.photos[0].image_url" :alt="gallery.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <div v-else class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"><component :is="Icons.ImageIcon" class="w-6 h-6 text-gray-400" /></div>
                            <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition-colors flex items-end p-3">
                                <span class="text-white text-xs font-medium line-clamp-1">{{ gallery.title }}</span>
                            </div>
                        </Link>
                    </div>
                    <div class="mt-6 text-right">
                        <Link :href="route('gallery.index')" class="text-sm font-medium text-blue-600 hover:text-blue-500">Lihat Semua Galeri &rarr;</Link>
                    </div>
                </div>
            </section>
            
            <!-- CTA Pengaduan -->
            <section class="scroll-mt-24">
                <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-3xl overflow-hidden shadow-lg relative">
                    <div class="absolute inset-0 opacity-10">
                        <svg class="h-full w-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <pattern id="pattern-cta" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                                <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5" />
                            </pattern>
                            <rect x="0" y="0" width="100" height="100" fill="url(#pattern-cta)" />
                        </svg>
                    </div>
                    <div class="relative max-w-7xl mx-auto py-12 px-6 sm:py-16 sm:px-12 lg:flex lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                                <span class="block">Punya Aspirasi atau Keluhan?</span>
                            </h2>
                            <p class="mt-4 text-lg leading-6 text-blue-100 max-w-2xl">
                                Sampaikan laporan pengaduan, kritik, maupun saran Anda secara langsung kepada Pemerintah Desa melalui layanan pengaduan terpadu.
                            </p>
                        </div>
                        <div class="mt-8 flex lg:mt-0 lg:flex-shrink-0 gap-4 flex-wrap">
                            <Link :href="route('complaints.create')" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-blue-600 bg-white hover:bg-blue-50 transition-colors shadow">
                                Buat Laporan
                            </Link>
                            <Link :href="route('documents.index')" class="inline-flex items-center justify-center px-6 py-3 border border-blue-400 text-base font-medium rounded-xl text-white hover:bg-blue-800 transition-colors shadow">
                                Unduh Dokumen
                            </Link>
                        </div>
                    </div>
                </div>
            </section>
            
        </div>
    </PublicLayout>
</template>

