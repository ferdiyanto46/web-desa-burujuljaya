<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
    bannerImage?: string;
}>();
</script>

<template>
    <div class="relative bg-gray-900 overflow-hidden h-[500px] lg:h-[600px] flex items-center">
        <div class="absolute inset-0">
            <img 
                :src="bannerImage || '/images/hero-bg.jpg'" 
                alt="Desa Burujuljaya" 
                class="w-full h-full object-cover object-center" 
                onerror="this.src='https://images.unsplash.com/photo-1596422846543-74c6ca7e42f1?auto=format&fit=crop&q=80&w=2000'"
            />
            <div class="absolute inset-0 bg-gray-900/60 mix-blend-multiply" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 opacity-80" aria-hidden="true"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 text-blue-200 text-sm font-medium border border-blue-500/30 mb-6 backdrop-blur-sm">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                    </span>
                    Sistem Informasi Desa
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold tracking-tight text-white mb-6 leading-tight">
                    Selamat Datang di <br/>
                    <span class="text-blue-400">Desa Burujuljaya</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-300 mb-8 max-w-2xl leading-relaxed">
                    Wujudkan transparansi, kemudahan akses informasi, dan pelayanan publik yang prima untuk masyarakat Desa Burujuljaya yang lebih maju dan sejahtera.
                </p>
                <div class="flex flex-wrap gap-4">
                    <Link :href="route('pages.profile')" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm">
                        Kenali Desa Kami
                    </Link>
                    <Link :href="route('complaints.create')" class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-xl text-white hover:bg-white/10 transition-colors backdrop-blur-sm">
                        Layanan Pengaduan
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
