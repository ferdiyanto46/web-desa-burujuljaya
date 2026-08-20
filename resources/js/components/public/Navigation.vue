<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from '@/lib/route';

const currentRoute = (route as any)().current() as string | undefined;

const isRouteActive = (pattern: string): boolean => {
    if (pattern === 'home' && currentRoute === 'home') {
        return true;
    }

    if (pattern !== 'home' && currentRoute?.startsWith(pattern)) {
        return true;
    }

    return false;
};

interface NavItem {
    name: string;
    route: string;
    pattern: string;
    hidden?: boolean;
}

const navigation: NavItem[] = [
    { name: 'Beranda', route: 'home', pattern: 'home' },
    { name: 'Profil Desa', route: 'profile.index', pattern: 'profile' },
    { name: 'Artikel', route: 'articles.index', pattern: 'articles' },
    { name: 'Galeri', route: 'gallery.index', pattern: 'gallery' },
    { name: 'Wisata', route: 'tourism.index', pattern: 'tourism', hidden: true }, // Dinonaktifkan sesuai permintaan
    { name: 'UMKM', route: 'umkm.index', pattern: 'umkm' },
    { name: 'Produk', route: 'products.index', pattern: 'products', hidden: true }, // Dinonaktifkan sesuai permintaan
    { name: 'Dokumen', route: 'documents.index', pattern: 'documents' },
    { name: 'Pengaduan', route: 'complaints.create', pattern: 'complaints' },
];

const visibleNavigation = computed(() => navigation.filter(item => !item.hidden));
</script>

<template>
    <div class="flex items-center space-x-8">
        <template v-for="item in visibleNavigation" :key="item.name">
            <Link 
                :href="route(item.route)"
                :class="[
                    isRouteActive(item.pattern) 
                        ? 'text-emerald-600 dark:text-emerald-400 font-semibold border-b-2 border-emerald-600 dark:border-emerald-400' 
                        : 'text-gray-600 dark:text-gray-300 hover:text-emerald-600 dark:hover:text-emerald-400 font-medium border-b-2 border-transparent hover:border-emerald-300 dark:hover:border-emerald-700',
                    'py-5 transition-all duration-200'
                ]"
            >
                {{ item.name }}
            </Link>
        </template>
    </div>
</template>
