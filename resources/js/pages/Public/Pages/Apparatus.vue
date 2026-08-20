<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    apparatuses: any[];
}>();
</script>

<template>
    <Head title="Aparatur Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Aparatur Desa" subtitle="Struktur organisasi dan pelayan masyarakat Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Profil Desa', route: 'public.profile.index' }, { name: 'Aparatur Desa' }]" />
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
                            <Link :href="route('pages.vision-mission')" class="block px-6 py-4 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 border-l-4 border-transparent hover:border-gray-300 transition-colors">
                                Visi & Misi
                            </Link>
                        </li>
                        <li>
                            <Link :href="route('pages.apparatus')" class="block px-6 py-4 text-blue-600 dark:text-blue-400 font-medium bg-blue-50/50 dark:bg-blue-900/10 border-l-4 border-blue-600">
                                Aparatur Desa
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Content -->
            <div class="lg:w-3/4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden p-8">
                    
                    <div v-if="apparatuses?.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                        <div v-for="person in apparatuses" :key="person.id" class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700 text-center flex flex-col items-center group hover:shadow-md transition-shadow">
                            <div class="w-32 h-32 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 mb-4 border-4 border-white dark:border-gray-800 shadow-sm relative">
                                <img v-if="person.photo_url" :src="person.photo_url" :alt="person.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                <div v-else class="absolute inset-0 flex items-center justify-center text-gray-400">
                                    <component :is="Icons.UserIcon" class="w-12 h-12" />
                                </div>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">{{ person.name }}</h3>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">{{ person.position?.name || 'Aparatur' }}</p>
                        </div>
                    </div>
                    
                    <div v-else class="text-center text-gray-500 py-12">
                        <component :is="Icons.UsersIcon" class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                        <p>Belum ada data aparatur desa.</p>
                    </div>

                </div>
            </div>
        </div>
    </PublicLayout>
</template>
