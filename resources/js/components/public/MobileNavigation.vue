<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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

const navigation = [
    { name: 'Beranda', route: 'home', pattern: 'home' },
    { name: 'Profil Desa', route: 'pages.profile', pattern: 'pages.profile' },
    { name: 'Artikel', route: 'articles.index', pattern: 'articles' },
    { name: 'Pengumuman', route: 'announcements.index', pattern: 'announcements' },
    { name: 'Agenda', route: 'agendas.index', pattern: 'agendas' },
    { name: 'Galeri', route: 'gallery.index', pattern: 'gallery' },
    { name: 'Video', route: 'videos.index', pattern: 'videos' },
    { name: 'Wisata', route: 'tourism.index', pattern: 'tourism' },
    { name: 'UMKM', route: 'umkm.index', pattern: 'umkm' },
    { name: 'Produk', route: 'products.index', pattern: 'products' },
    { name: 'Dokumen', route: 'documents.index', pattern: 'documents' },
    { name: 'Pengaduan', route: 'complaints.create', pattern: 'complaints' },
];


</script>

<template>
    <div class="px-4 py-2 space-y-1 bg-white dark:bg-gray-800">
        <template v-for="item in navigation" :key="item.name">
            <Link 
                :href="route(item.route)"
                @click="layoutStore.toggleMobileDrawer()"
                :class="[
                    isRouteActive(item.pattern) 
                        ? 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 font-semibold border-l-4 border-blue-600 dark:border-blue-400' 
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white border-l-4 border-transparent',
                    'block px-3 py-3 text-base rounded-r-md transition-colors'
                ]"
            >
                {{ item.name }}
            </Link>
        </template>
    </div>
</template>
