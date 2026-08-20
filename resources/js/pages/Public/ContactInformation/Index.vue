<script setup lang="ts">
import { Head as AppHead, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';

const props = defineProps<{
    contacts: {
        data: any[];
        links: any[];
    };
}>();
</script>

<template>
    <AppHead title="Informasi Kontak & Publik" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Informasi Kontak" subtitle="Hubungi kami atau temukan lokasi layanan publik desa." />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Kontak' }]" />
        </template>
        
        <div class="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div v-if="contacts.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div 
                        v-for="contact in contacts.data" 
                        :key="contact.id" 
                        class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-lg transition-all duration-300"
                    >
                        <div class="p-6 md:p-8 flex-grow flex flex-col">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">
                                <Link :href="route('contact-information.show', contact.slug)">{{ contact.title }}</Link>
                            </h3>
                            <p v-if="contact.excerpt" class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 mb-4">{{ contact.excerpt }}</p>
                            
                            <div class="space-y-3 mb-6 flex-grow">
                                <p v-if="contact.address" class="text-sm text-gray-600 dark:text-gray-400 flex items-start gap-2">
                                    <span class="mt-0.5">📍</span>
                                    <span>{{ contact.address }}</span>
                                </p>
                                <p v-if="contact.phone || contact.mobile" class="text-sm text-gray-600 dark:text-gray-400 flex items-start gap-2">
                                    <span class="mt-0.5">📞</span>
                                    <span>{{ contact.phone || contact.mobile }}</span>
                                </p>
                                <p v-if="contact.email" class="text-sm text-gray-600 dark:text-gray-400 flex items-start gap-2">
                                    <span class="mt-0.5">✉️</span>
                                    <span>{{ contact.email }}</span>
                                </p>
                            </div>
                            
                            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                <Link :href="route('contact-information.show', contact.slug)" class="w-full inline-flex justify-center items-center px-4 py-2 border border-blue-600 text-sm font-medium rounded-lg text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                                    Lihat Selengkapnya
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="contacts.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in contacts.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === contacts.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === contacts.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Informasi Tidak Ditemukan" 
                message="Maaf, informasi kontak publik belum tersedia saat ini." 
            />
        </div>
    </PublicLayout>
</template>
