<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from '@/lib/route';
import { useLayoutStore } from '@/stores/layoutStore';

const layoutStore = useLayoutStore();
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
    <div class="px-4 py-2 space-y-1 bg-white dark:bg-gray-800">
        <template v-for="item in visibleNavigation" :key="item.name">
            <Link 
                :href="route(item.route)"
                @click="layoutStore.toggleMobileDrawer()"
                :class="[
                    isRouteActive(item.pattern) 
                        ? 'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 font-semibold border-l-4 border-emerald-600 dark:border-emerald-400' 
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white border-l-4 border-transparent',
                    'block px-3 py-3 text-base rounded-r-md transition-colors'
                ]"
            >
                {{ item.name }}
            </Link>
        </template>
    </div>
</template>
