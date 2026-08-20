<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    profile: any;
    missions: any[];
}>();
</script>

<template>
    <Head title="Visi & Misi" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Visi & Misi" subtitle="Tujuan dan arah pembangunan Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Profil Desa', route: 'public.profile.index' }, { name: 'Visi & Misi' }]" />
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
                            <Link :href="route('pages.profile')" class="block px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300 transition-colors">
                                Profil & Sejarah
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('pages.vision-mission')" class="block px-6 py-4 text-blue-600 dark:text-blue-400 font-medium bg-blue-50/50 dark:bg-blue-900/10 border-l-4 border-blue-600">
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
                    
                    <!-- Vision -->
                    <div class="text-center mb-16">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 mb-6">
                            <component :is="Icons.SunIcon" class="w-8 h-8" />
                        </div>
                        <h2 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-4">Visi</h2>
                        <blockquote class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight">
                            "{{ profile?.vision || 'Belum ada data visi desa.' }}"
                        </blockquote>
                    </div>
                    
                    <!-- Missions -->
                    <div>
                        <h2 class="text-sm font-bold text-blue-600 dark:text-blue-400 uppercase tracking-widest mb-8 text-center">Misi</h2>
                        <div v-if="missions?.length > 0" class="grid gap-6">
                            <div v-for="(mission, index) in missions" :key="mission.id" class="flex gap-6 p-6 rounded-2xl bg-gray-50 dark:bg-gray-800/50 border border-gray-100 dark:border-gray-700">
                                <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-600 text-white flex items-center justify-center text-xl font-bold shadow-sm">
                                    {{ index + 1 }}
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ mission.title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ mission.description }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center text-gray-500 py-8 bg-gray-50 dark:bg-gray-800/50 rounded-2xl">
                            Belum ada rincian misi desa.
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </PublicLayout>
</template>
