<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    profile: any;
}>();
</script>

<template>
    <Head title="Profil Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Profil Desa" subtitle="Sejarah, identitas, dan gambaran umum wilayah Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Profil Desa' }]" />
        </template>
        
        <div class="flex flex-col lg:flex-row gap-8 py-4">
            <!-- Sidebar Navigation -->
            <div class="lg:w-1/4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden sticky top-24">
                    <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-b border-gray-100 dark:border-gray-700">
                        <h3 class="font-bold text-gray-900 dark:text-white text-lg">Informasi Desa</h3>
                    </div>
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        <li>
                            <Link :href="route('pages.profile')" class="block px-6 py-4 text-blue-600 dark:text-blue-400 font-medium bg-blue-50/50 dark:bg-blue-900/10 border-l-4 border-blue-600">
                                Profil & Sejarah
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('pages.vision-mission')" class="block px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300 transition-colors">
                                Visi & Misi
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('pages.apparatus')" class="block px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300 transition-colors">
                                Aparatur Desa
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:w-3/4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-8 md:p-12">
                    <div class="flex flex-col md:flex-row items-center md:items-start gap-8 mb-8">
                        <div v-if="profile?.logo_url" class="flex-shrink-0">
                            <img :src="profile.logo_url" alt="Logo Desa" class="w-32 h-32 md:w-48 md:h-48 object-contain" />
                        </div>
                        <div>
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Sejarah & Identitas</h2>
                            <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300" v-html="profile?.history || 'Belum ada data sejarah desa.'"></div>
                        </div>
                    </div>
                    
                    <hr class="border-gray-100 dark:border-gray-700 my-8" />
                    
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Gambaran Umum</h2>
                    <div class="prose dark:prose-invert max-w-none text-gray-600 dark:text-gray-300" v-html="profile?.description || 'Belum ada gambaran umum.'"></div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
