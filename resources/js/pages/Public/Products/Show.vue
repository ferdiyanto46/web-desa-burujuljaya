<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    product: any;
}>();


</script>

<template>
    <Head :title="product.name" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Produk', route: 'public.products.index' }, { name: product.name }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-6xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="flex flex-col md:flex-row">
                    
                    <!-- Image Area -->
                    <div class="md:w-1/2 p-8 md:p-12 md:border-r border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/30 flex items-center justify-center relative">
                        <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-auto max-h-[500px] object-contain rounded-xl shadow-sm" />
                        <component v-else :is="Icons.PackageIcon" class="w-32 h-32 text-gray-300 dark:text-gray-600" />
                        
                        <div class="absolute top-8 left-8">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 uppercase tracking-wider shadow-sm">
                                {{ product.category?.name || 'Umum' }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Info Area -->
                    <div class="md:w-1/2 p-8 md:p-12 flex flex-col">
                        <Link v-if="product.umkm" :href="route('umkm.show', product.umkm.slug)" class="inline-flex items-center text-sm font-bold text-blue-600 dark:text-blue-400 mb-3 hover:underline">
                            <component :is="Icons.StoreIcon" class="w-4 h-4 mr-1.5" />
                            {{ product.umkm.name }}
                        </Link>
                        
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white mb-4 leading-tight">{{ product.name }}</h1>
                        
                        <div class="text-3xl font-extrabold text-gray-900 dark:text-white mb-8 pb-8 border-b border-gray-100 dark:border-gray-700">
                            Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}
                        </div>
                        
                        <div class="mb-8 flex-grow">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3">Deskripsi Produk</h3>
                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-600 dark:text-gray-300 whitespace-pre-line">
                                {{ product.description || 'Tidak ada deskripsi tersedia.' }}
                            </div>
                        </div>
                        
                        <div class="pt-8 border-t border-gray-100 dark:border-gray-700 flex flex-col gap-4">
                            <!-- Contact WhatsApp -->
                            <a v-if="product.umkm && product.umkm.phone" :href="`https://wa.me/${product.umkm.phone.replace(/[^0-9]/g, '')}?text=Halo, saya tertarik dengan produk ${product.name} (Rp ${new Intl.NumberFormat('id-ID').format(product.price)}) yang saya lihat di website Desa.`" target="_blank" class="w-full inline-flex justify-center items-center px-6 py-4 border border-transparent text-base font-bold rounded-xl shadow-sm text-white bg-green-500 hover:bg-green-600 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                <component :is="Icons.PhoneIcon" class="w-5 h-5 mr-2" /> Beli via WhatsApp
                            </a>
                            <div v-else class="w-full inline-flex justify-center items-center px-6 py-4 border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 text-gray-500 text-base font-medium rounded-xl text-center">
                                Kontak Penjual Tidak Tersedia
                            </div>
                            
                            <div class="flex items-center justify-between mt-4">
                                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Bagikan Produk Ini:</span>
                                <ShareButtons :title="`Produk: ${product.name}`" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- UMKM Details Section if available -->
            <div v-if="product.umkm" class="mt-12 bg-blue-50 dark:bg-gray-800 rounded-3xl p-8 md:p-12 border border-blue-100 dark:border-gray-700 flex flex-col md:flex-row items-center justify-between gap-8">
                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Tertarik dengan {{ product.umkm.name }}?</h3>
                    <p class="text-gray-600 dark:text-gray-300 max-w-2xl">Jelajahi produk menarik lainnya dan ketahui lebih lanjut mengenai profil UMKM ini.</p>
                </div>
                <Link :href="route('umkm.show', product.umkm.slug)" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 shadow-sm text-base font-medium rounded-xl text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors whitespace-nowrap">
                    Kunjungi Halaman UMKM
                </Link>
            </div>
        </div>
    </PublicLayout>
</template>
