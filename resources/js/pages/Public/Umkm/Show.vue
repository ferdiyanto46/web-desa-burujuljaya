<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    umkm: any;
}>();


</script>

<template>
    <Head :title="umkm.name" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'UMKM', route: 'public.umkm.index' }, { name: umkm.name }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-7xl mx-auto">
            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                <!-- Main Content -->
                <div class="lg:w-2/3">
                    <!-- Profile Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12 mb-8 flex flex-col md:flex-row gap-8 items-center md:items-start">
                        <div class="w-32 h-32 md:w-48 md:h-48 flex-shrink-0 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden shadow-inner border-4 border-white dark:border-gray-800">
                            <img v-if="umkm.logo_url" :src="umkm.logo_url" :alt="umkm.name" class="w-full h-full object-cover" />
                            <component v-else :is="Icons.StoreIcon" class="w-16 h-16 text-gray-400" />
                        </div>
                        <div class="text-center md:text-left">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 mb-3">
                                {{ umkm.category?.name || 'UMKM' }}
                            </span>
                            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ umkm.name }}</h1>
                            <p class="text-lg text-gray-600 dark:text-gray-300 mb-6">Milik: <span class="font-bold text-gray-900 dark:text-white">{{ umkm.owner_name }}</span></p>
                            
                            <div class="flex flex-wrap gap-4 justify-center md:justify-start">
                                <a v-if="umkm.phone" :href="`https://wa.me/${umkm.phone.replace(/[^0-9]/g, '')}`" target="_blank" class="inline-flex items-center px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm font-medium rounded-xl transition-colors shadow-sm">
                                    <component :is="Icons.PhoneIcon" class="w-4 h-4 mr-2" /> Hubungi via WhatsApp
                                </a>
                                <ShareButtons :title="umkm.name" />
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12 mb-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Tentang UMKM</h2>
                        <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                            <div v-html="umkm.description || '<p>Tidak ada deskripsi tersedia.</p>'"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="lg:w-1/3">
                    <div class="bg-indigo-50 dark:bg-gray-800 rounded-3xl shadow-sm border border-indigo-100 dark:border-gray-700 p-8 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-indigo-200 dark:border-gray-700">Informasi Kontak</h3>
                        
                        <div class="space-y-6">
                            <!-- Owner -->
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                    <component :is="Icons.UserIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Nama Pemilik</p>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ umkm.owner_name }}</p>
                                </div>
                            </div>
                            
                            <!-- Address -->
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                    <component :is="Icons.MapIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Alamat</p>
                                    <p class="font-bold text-gray-900 dark:text-white">{{ umkm.address }}</p>
                                    <a v-if="umkm.google_maps_url" :href="umkm.google_maps_url" target="_blank" class="inline-flex items-center mt-2 text-sm text-indigo-600 dark:text-indigo-400 hover:underline">
                                        <component :is="Icons.MapIcon" class="w-4 h-4 mr-1" />
                                        Lihat Lokasi
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Contact -->
                            <div class="flex items-start gap-4" v-if="umkm.phone">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 flex-shrink-0">
                                    <component :is="Icons.PhoneIcon" class="w-5 h-5" />
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium uppercase tracking-wider mb-1">Nomor Telepon/WA</p>
                                    <a :href="`tel:${umkm.phone}`" class="font-bold text-indigo-600 dark:text-indigo-400 hover:underline">{{ umkm.phone }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products List -->
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-12">
                <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 text-center">Produk dari {{ umkm.name }}</h2>
                
                <div v-if="umkm.products && umkm.products.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <Link 
                        v-for="product in umkm.products" 
                        :key="product.id"
                        :href="route('products.show', product.slug)"
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col group hover:shadow-md hover:-translate-y-1 transition-all duration-300"
                    >
                        <div class="h-48 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                            <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center"><component :is="Icons.PackageIcon" class="w-12 h-12 text-gray-400" /></div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ product.name }}</h3>
                            <div class="mt-auto flex justify-between items-center">
                                <span class="font-extrabold text-indigo-600 dark:text-indigo-400">Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}</span>
                            </div>
                        </div>
                    </Link>
                </div>
                
                <div v-else class="text-center py-12 bg-gray-50 dark:bg-gray-800/50 rounded-3xl border border-gray-100 dark:border-gray-700">
                    <component :is="Icons.PackageIcon" class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">Belum Ada Produk</h3>
                    <p class="text-gray-500 dark:text-gray-400">UMKM ini belum menambahkan katalog produk.</p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
