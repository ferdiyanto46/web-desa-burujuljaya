<script setup lang="ts">
import { Head as AppHead } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { ContactInformation } from '@/types/contact-information';

const props = defineProps<{
    contact: ContactInformation;
}>();

const formatWa = (wa: string) => {
    // Remove non-numeric characters
    let formatted = wa.replace(/\D/g, '');

    // If starts with 0, replace with 62 (Indonesia country code)
    if (formatted.startsWith('0')) {
        formatted = '62' + formatted.substring(1);
    }

    return formatted;
};
</script>

<template>
    <AppHead :title="contact.title">
        <meta name="description" :content="contact.excerpt || contact.title" />
        <meta property="og:title" :content="contact.title" />
        <meta property="og:description" :content="contact.excerpt || contact.title" />
        <meta v-if="contact.featured_image_path" property="og:image" :content="`/storage/${contact.featured_image_path}`" />
    </AppHead>

    <PublicLayout>
        <template #banner>
            <PageBanner :title="contact.title" :subtitle="contact.excerpt || 'Informasi Kontak & Layanan'" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Kontak', route: route('contact-information.index') }, { name: contact.title }]" />
        </template>
        
        <div class="py-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Utama: Konten & Peta -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Featured Image -->
                    <div v-if="contact.featured_image_path" class="rounded-3xl overflow-hidden shadow-md">
                        <img :src="`/storage/${contact.featured_image_path}`" :alt="contact.title" class="w-full h-auto object-cover max-h-[400px]" />
                    </div>

                    <!-- Description -->
                    <div v-if="contact.description" class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <!-- eslint-disable-next-line vue/no-v-html -->
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300" v-html="contact.description"></div>
                    </div>

                    <!-- Google Maps Embed -->
                    <div v-if="contact.google_maps_embed" class="bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                            <span>🗺️</span> Peta Lokasi
                        </h3>
                        <!-- eslint-disable-next-line vue/no-v-html -->
                        <div class="w-full overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700" v-html="contact.google_maps_embed"></div>
                        <div v-if="contact.google_maps_url" class="mt-4 text-center">
                            <a :href="contact.google_maps_url" target="_blank" class="inline-flex items-center justify-center px-4 py-2 border border-blue-600 rounded-lg text-sm font-medium text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                                Buka di Google Maps
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kolom Samping: Informasi Detail -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 sticky top-24">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">Info Kontak</h3>
                        
                        <div class="space-y-6">
                            <!-- Alamat -->
                            <div v-if="contact.address" class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-50 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                                    📍
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Alamat</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed whitespace-pre-wrap">{{ contact.address }}</p>
                                </div>
                            </div>

                            <!-- Kontak Darurat -->
                            <div v-if="contact.emergency_contact" class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-50 dark:bg-red-900/30 flex items-center justify-center text-red-600 dark:text-red-400">
                                    🚨
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Kontak Darurat</h4>
                                    <p class="text-sm font-bold text-red-600 dark:text-red-400">{{ contact.emergency_contact }}</p>
                                </div>
                            </div>

                            <!-- Jam Kerja -->
                            <div v-if="contact.office_hours" class="flex gap-4">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-green-50 dark:bg-green-900/30 flex items-center justify-center text-green-600 dark:text-green-400">
                                    🕒
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-1">Jam Operasional</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">{{ contact.office_hours }}</p>
                                </div>
                            </div>

                            <!-- Email & Web -->
                            <div v-if="contact.email || contact.website" class="pt-4 border-t border-gray-100 dark:border-gray-700 space-y-4">
                                <div v-if="contact.email" class="flex gap-4">
                                    <div class="flex-shrink-0 text-xl">✉️</div>
                                    <div class="overflow-hidden">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Email</h4>
                                        <a :href="`mailto:${contact.email}`" class="text-sm text-blue-600 hover:underline truncate block">{{ contact.email }}</a>
                                    </div>
                                </div>
                                <div v-if="contact.website" class="flex gap-4">
                                    <div class="flex-shrink-0 text-xl">🌐</div>
                                    <div class="overflow-hidden">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-0.5">Website</h4>
                                        <a :href="contact.website" target="_blank" class="text-sm text-blue-600 hover:underline truncate block">{{ contact.website }}</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Telepon & WA Buttons -->
                            <div v-if="contact.phone || contact.mobile || contact.whatsapp" class="pt-6">
                                <div class="space-y-3">
                                    <a v-if="contact.whatsapp" :href="`https://wa.me/${formatWa(contact.whatsapp)}`" target="_blank" class="w-full flex items-center justify-center gap-2 bg-[#25D366] hover:bg-[#128C7E] text-white px-4 py-3 rounded-xl font-medium transition-colors shadow-sm">
                                        💬 Chat WhatsApp
                                    </a>
                                    
                                    <a v-if="contact.phone" :href="`tel:${contact.phone}`" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-medium transition-colors shadow-sm">
                                        📞 Hubungi {{ contact.phone }}
                                    </a>

                                    <a v-if="contact.mobile && !contact.phone" :href="`tel:${contact.mobile}`" class="w-full flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-xl font-medium transition-colors shadow-sm">
                                        📱 Hubungi {{ contact.mobile }}
                                    </a>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div v-if="contact.facebook || contact.instagram || contact.youtube || contact.tiktok" class="pt-6 border-t border-gray-100 dark:border-gray-700">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 text-center">Media Sosial</h4>
                                <div class="flex justify-center gap-3">
                                    <a v-if="contact.facebook" :href="contact.facebook" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-blue-100 dark:bg-gray-700 dark:hover:bg-blue-900/50 flex items-center justify-center text-xl transition-colors" title="Facebook">
                                        📘
                                    </a>
                                    <a v-if="contact.instagram" :href="contact.instagram" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-pink-100 dark:bg-gray-700 dark:hover:bg-pink-900/50 flex items-center justify-center text-xl transition-colors" title="Instagram">
                                        📸
                                    </a>
                                    <a v-if="contact.youtube" :href="contact.youtube" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-red-100 dark:bg-gray-700 dark:hover:bg-red-900/50 flex items-center justify-center text-xl transition-colors" title="YouTube">
                                        ▶️
                                    </a>
                                    <a v-if="contact.tiktok" :href="contact.tiktok" target="_blank" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 flex items-center justify-center text-xl transition-colors" title="TikTok">
                                        🎵
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
