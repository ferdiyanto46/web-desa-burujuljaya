<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';
import type { Tourism } from '@/types';

defineProps<{
    tourism: Tourism;
}>();
</script>

<template>
    <Head>
        <title>{{ tourism.seo?.meta_title || tourism.title }}</title>
        <meta name="description" :content="tourism.seo?.meta_description || tourism.excerpt || ''" />
    </Head>

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[
                { name: 'Pariwisata', route: route('tourism.index') },
                { name: tourism.title }
            ]" />
        </template>
        
        <div class="py-8 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div v-if="tourism.featured_image_url" class="w-full h-64 md:h-96 relative">
                    <img :src="tourism.featured_image_url" :alt="tourism.title" class="w-full h-full object-cover" />
                </div>
                
                <div class="p-6 md:p-10">
                    <div class="flex items-center gap-4 mb-6">
                        <span v-if="tourism.category" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                            {{ tourism.category.name }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ tourism.view_count }}x dilihat
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ tourism.title }}
                    </h1>

                    <div class="prose prose-blue dark:prose-invert max-w-none mb-10" v-html="tourism.description"></div>

                    <!-- Information section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 dark:border-gray-700 pt-8">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Informasi</h3>
                            <ul class="space-y-4">
                                <li v-if="tourism.address" class="flex">
                                    <span class="font-medium w-32 text-gray-700 dark:text-gray-300">Alamat:</span>
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ tourism.address }}</span>
                                </li>
                                <li v-if="tourism.opening_hours" class="flex">
                                    <span class="font-medium w-32 text-gray-700 dark:text-gray-300">Jam Buka:</span>
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ tourism.opening_hours }}</span>
                                </li>
                                <li v-if="tourism.ticket_price" class="flex">
                                    <span class="font-medium w-32 text-gray-700 dark:text-gray-300">Tiket:</span>
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ tourism.ticket_price }}</span>
                                </li>
                                <li v-if="tourism.contact_person" class="flex">
                                    <span class="font-medium w-32 text-gray-700 dark:text-gray-300">Kontak:</span>
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ tourism.contact_person }} <span v-if="tourism.phone">({{ tourism.phone }})</span></span>
                                </li>
                                <li v-if="tourism.website" class="flex">
                                    <span class="font-medium w-32 text-gray-700 dark:text-gray-300">Website:</span>
                                    <a :href="tourism.website" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex-1 truncate">{{ tourism.website }}</a>
                                </li>
                            </ul>
                            
                            <div v-if="tourism.google_maps_url" class="mt-6">
                                <a :href="tourism.google_maps_url" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Buka di Google Maps
                                </a>
                            </div>
                        </div>

                        <!-- Facilities -->
                        <div v-if="tourism.facilities && tourism.facilities.length > 0">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Fasilitas</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div v-for="facility in tourism.facilities" :key="facility.id" class="flex items-center p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                    <span class="text-gray-700 dark:text-gray-300">{{ facility.name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery -->
                    <div v-if="tourism.gallery && tourism.gallery.length > 0" class="mt-12 border-t border-gray-100 dark:border-gray-700 pt-8">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Galeri Foto</h3>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            <div v-for="photo in tourism.gallery" :key="photo.id" class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
                                <img :src="photo.file_url" :alt="photo.caption || tourism.title" class="w-full h-full object-cover hover:scale-105 transition-transform duration-300" />
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </PublicLayout>
</template>
