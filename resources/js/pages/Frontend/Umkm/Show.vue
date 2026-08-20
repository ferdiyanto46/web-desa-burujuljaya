<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { StoreIcon, MapPinIcon, PhoneIcon, MessageCircleIcon, MailIcon, GlobeIcon, InstagramIcon, FacebookIcon, ClockIcon } from 'lucide-vue-next'
import { computed } from 'vue'
import Breadcrumb from '@/components/public/Breadcrumb.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { Umkm } from '@/types/umkm'

interface Props {
    umkm: Umkm | { data: Umkm }
}

const props = defineProps<Props>()

const resolvedUmkm = computed<Umkm>(() => {
    return ((props.umkm as any)?.data || props.umkm) as Umkm
})

const getUmkmImage = (path?: string | null): string | null => {
    if (!path) return null;
    return path.startsWith('http') ? path : `/storage/${path.replace(/^\//, '')}`;
};
</script>

<template>
    <Head>
        <title>{{ resolvedUmkm.title }} | UMKM Desa</title>
        <meta name="description" :content="resolvedUmkm.description?.replace(/<[^>]*>?/gm, '').substring(0, 160) || ''" />
    </Head>

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[
                { name: 'UMKM', route: route('umkm.index') },
                { name: resolvedUmkm.title }
            ]" />
        </template>
        
        <div class="py-8 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <article class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="w-full h-64 md:h-[400px] relative bg-gray-200 dark:bg-gray-700">
                    <img 
                        v-if="getUmkmImage(resolvedUmkm.featured_image_path)" 
                        :src="getUmkmImage(resolvedUmkm.featured_image_path)!" 
                        :alt="resolvedUmkm.title" 
                        class="w-full h-full object-cover" 
                    />
                    <div v-else class="absolute inset-0 flex items-center justify-center">
                        <StoreIcon class="w-20 h-20 text-gray-400" />
                    </div>
                </div>
                
                <div class="p-6 md:p-10">
                    <div class="flex flex-wrap items-center gap-4 mb-6">
                        <span v-if="resolvedUmkm.category" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                            {{ resolvedUmkm.category.name }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            {{ resolvedUmkm.view_count || 0 }}x dilihat
                        </span>
                        <span v-if="resolvedUmkm.owner_name" class="text-sm font-medium text-gray-700 dark:text-gray-300 ml-auto">
                            Pemilik: {{ resolvedUmkm.owner_name }}
                        </span>
                    </div>

                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6">
                        {{ resolvedUmkm.title }}
                    </h1>

                    <div class="prose prose-green dark:prose-invert max-w-none mb-10" v-html="resolvedUmkm.description"></div>

                    <!-- Information section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t border-gray-100 dark:border-gray-700 pt-8">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-2">Informasi Umum</h3>
                            <ul class="space-y-4">
                                <li v-if="resolvedUmkm.address" class="flex gap-3">
                                    <MapPinIcon class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ resolvedUmkm.address }}</span>
                                </li>
                                <li v-if="resolvedUmkm.operating_hours" class="flex gap-3">
                                    <ClockIcon class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" />
                                    <span class="text-gray-600 dark:text-gray-400 flex-1">{{ resolvedUmkm.operating_hours }}</span>
                                </li>
                            </ul>
                            
                            <div v-if="resolvedUmkm.google_maps_url" class="mt-6">
                                <a :href="resolvedUmkm.google_maps_url" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <MapPinIcon class="w-4 h-4 mr-2" />
                                    Buka di Google Maps
                                </a>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-2">Kontak & Media Sosial</h3>
                            <ul class="space-y-4">
                                <li v-if="resolvedUmkm.phone" class="flex gap-3 items-center">
                                    <PhoneIcon class="w-5 h-5 text-gray-400" />
                                    <a :href="`tel:${resolvedUmkm.phone}`" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">{{ resolvedUmkm.phone }}</a>
                                </li>
                                <li v-if="resolvedUmkm.whatsapp" class="flex gap-3 items-center">
                                    <MessageCircleIcon class="w-5 h-5 text-green-500" />
                                    <a :href="`https://wa.me/${resolvedUmkm.whatsapp.replace(/\D/g,'')}`" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">{{ resolvedUmkm.whatsapp }}</a>
                                </li>
                                <li v-if="resolvedUmkm.email" class="flex gap-3 items-center">
                                    <MailIcon class="w-5 h-5 text-gray-400" />
                                    <a :href="`mailto:${resolvedUmkm.email}`" class="text-gray-600 dark:text-gray-400 hover:text-green-600 dark:hover:text-green-400">{{ resolvedUmkm.email }}</a>
                                </li>
                                <li v-if="resolvedUmkm.website" class="flex gap-3 items-center">
                                    <GlobeIcon class="w-5 h-5 text-gray-400" />
                                    <a :href="resolvedUmkm.website" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline flex-1 truncate">{{ resolvedUmkm.website }}</a>
                                </li>
                                <li v-if="resolvedUmkm.instagram" class="flex gap-3 items-center">
                                    <InstagramIcon class="w-5 h-5 text-pink-500" />
                                    <a :href="`https://instagram.com/${resolvedUmkm.instagram.replace('@', '')}`" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-pink-600 dark:hover:text-pink-400">{{ resolvedUmkm.instagram }}</a>
                                </li>
                                <li v-if="resolvedUmkm.facebook" class="flex gap-3 items-center">
                                    <FacebookIcon class="w-5 h-5 text-blue-500" />
                                    <a :href="resolvedUmkm.facebook" target="_blank" class="text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400">Facebook Page</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    
                    <!-- Gallery -->
                    <div v-if="resolvedUmkm.photos && resolvedUmkm.photos.length > 0" class="mt-12 border-t border-gray-100 dark:border-gray-700 pt-8">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Galeri Foto</h2>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="photo in resolvedUmkm.photos" :key="photo.id" class="aspect-square rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700 cursor-pointer shadow-sm">
                                <img :src="getUmkmImage(photo.file_path)!" :alt="photo.caption || resolvedUmkm.title" class="w-full h-full object-cover hover:scale-110 transition-transform duration-300" />
                            </div>
                        </div>
                    </div>
                </div>
            </article>
        </div>
    </PublicLayout>
</template>
