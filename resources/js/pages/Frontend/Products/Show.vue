<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ShoppingBagIcon, StoreIcon, MessageCircleIcon, MapPinIcon } from 'lucide-vue-next'
import Breadcrumb from '@/components/public/Breadcrumb.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { Product } from '@/types/product'

defineProps<{
    product: Product
}>()
</script>

<template>
    <Head>
        <title>{{ product.title }} | Produk Desa</title>
        <meta name="description" :content="product.description?.replace(/<[^>]*>?/gm, '').substring(0, 160) || ''" />
    </Head>

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[
                { name: 'Produk', route: route('products.index') },
                { name: product.title }
            ]" />
        </template>
        
        <div class="py-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 md:gap-8">
                    <!-- Image Section -->
                    <div class="h-64 sm:h-80 md:h-full relative bg-gray-200 dark:bg-gray-700">
                        <img v-if="product.featured_image_path" :src="`/storage/${product.featured_image_path}`" :alt="product.title" class="w-full h-full object-cover" />
                        <div v-else class="absolute inset-0 flex items-center justify-center">
                            <ShoppingBagIcon class="w-20 h-20 text-gray-400" />
                        </div>
                    </div>
                    
                    <!-- Content Section -->
                    <div class="p-6 md:p-10 flex flex-col">
                        <div class="flex flex-wrap items-center gap-4 mb-4">
                            <span v-if="product.category" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                {{ product.category.name }}
                            </span>
                        </div>

                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2 leading-tight">
                            {{ product.title }}
                        </h1>
                        
                        <div class="text-2xl font-bold text-primary mb-6">
                            Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}
                        </div>

                        <div class="prose prose-blue dark:prose-invert max-w-none mb-8 flex-grow" v-html="product.description"></div>

                        <!-- Action / UMKM Info -->
                        <div class="border-t border-gray-100 dark:border-gray-700 pt-6 mt-auto">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Dijual oleh:</h3>
                            <div v-if="product.umkm" class="flex flex-col gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="bg-primary/10 p-3 rounded-xl text-primary">
                                        <StoreIcon class="w-6 h-6" />
                                    </div>
                                    <div>
                                        <Link :href="route('umkm.show', product.umkm.slug || '')" class="text-lg font-bold hover:text-primary transition-colors">
                                            {{ product.umkm.title }}
                                        </Link>
                                    </div>
                                </div>
                                <div class="flex gap-2 mt-2">
                                    <Link :href="route('umkm.show', product.umkm.slug || '')" class="flex-1 inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md shadow-sm text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                                        <StoreIcon class="w-4 h-4 mr-2" />
                                        Kunjungi Toko
                                    </Link>
                                </div>
                            </div>
                            <div v-else>
                                <p class="text-sm text-gray-500">UMKM tidak tersedia.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Gallery -->
                <div v-if="product.photos && product.photos.length > 0" class="p-6 md:p-10 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Galeri Produk</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div v-for="photo in product.photos" :key="photo.id" class="aspect-square rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 cursor-pointer shadow-sm">
                            <img :src="`/storage/${photo.image_path}`" :alt="photo.caption || product.title" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </PublicLayout>
</template>
