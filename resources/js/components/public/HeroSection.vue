<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { ArrowRight, ShieldCheck, MessageSquare } from 'lucide-vue-next';

defineProps<{
    bannerImage?: string;
    title?: string;
    subtitle?: string;
}>();

const page = usePage();
const village = page.props.village as any;
</script>

<template>
    <div class="relative bg-gray-900 overflow-hidden min-h-[540px] lg:min-h-[620px] flex items-center">
        <!-- Background Image with Gradients -->
        <div class="absolute inset-0 z-0">
            <img 
                :src="bannerImage || '/images/hero-bg.jpg'" 
                :alt="village?.village_name || 'Desa Burujuljaya'" 
                class="w-full h-full object-cover object-center transform scale-105 transition-transform duration-1000" 
                onerror="this.src='https://images.unsplash.com/photo-1596422846543-74c6ca7e42f1?auto=format&fit=crop&q=80&w=2000'"
            />
            <div class="absolute inset-0 bg-gradient-to-r from-gray-950/90 via-gray-900/75 to-transparent" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-gray-950 via-transparent to-black/30" aria-hidden="true"></div>
        </div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-16">
            <div class="max-w-3xl">
                <!-- Badge -->
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300 text-xs sm:text-sm font-medium border border-emerald-500/30 mb-6 backdrop-blur-md">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span>Website Resmi Pemerintah Desa</span>
                </div>

                <!-- Headline -->
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight text-white mb-6 leading-tight">
                    {{ title || `Selamat Datang di Desa ${village?.village_name || 'Burujuljaya'}` }}
                </h1>

                <!-- Subtitle -->
                <p class="text-base sm:text-lg lg:text-xl text-gray-200 mb-8 max-w-2xl leading-relaxed font-normal">
                    {{ subtitle || 'Pusat informasi publik, transparansi pembangunan, dan pelayanan masyarakat Desa Burujuljaya terpadu secara online.' }}
                </p>

                <!-- CTA Buttons -->
                <div class="flex flex-wrap items-center gap-4">
                    <Link 
                        :href="route('profile.index')" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-white bg-emerald-600 hover:bg-emerald-500 font-semibold text-sm sm:text-base shadow-lg shadow-emerald-900/40 transition-all hover:scale-105"
                    >
                        <span>Profil & Sejarah Desa</span>
                        <ArrowRight class="w-4 h-4" />
                    </Link>

                    <Link 
                        :href="route('complaints.create')" 
                        class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl text-white bg-white/10 hover:bg-white/20 border border-white/20 font-semibold text-sm sm:text-base backdrop-blur-md transition-all hover:scale-105"
                    >
                        <MessageSquare class="w-4 h-4 text-emerald-400" />
                        <span>Layanan Pengaduan Warga</span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
