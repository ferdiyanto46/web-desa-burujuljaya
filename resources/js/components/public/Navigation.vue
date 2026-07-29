<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
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

const navigation = [
    { name: 'Beranda', route: 'home', pattern: 'home' },
    { name: 'Profil Desa', route: 'pages.profile', pattern: 'pages.profile' },
    { name: 'Informasi', route: 'articles.index', pattern: 'articles' }, // Will be dropdown later
    { name: 'Potensi', route: 'tourism.index', pattern: 'tourism' }, // Will be dropdown later
    { name: 'Layanan', route: 'documents.index', pattern: 'documents' },
    { name: 'Pengaduan', route: 'complaints.create', pattern: 'complaints' },
];
</script>

<template>
    <div class="flex items-center space-x-8">
        <template v-for="item in navigation" :key="item.name">
            <Link 
                :href="route(item.route)"
                :class="[
                    isRouteActive(item.pattern) 
                        ? 'text-blue-600 dark:text-blue-400 font-semibold border-b-2 border-blue-600 dark:border-blue-400' 
                        : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium border-b-2 border-transparent hover:border-blue-300 dark:hover:border-blue-700',
                    'py-5 transition-all duration-200'
                ]"
            >
                {{ item.name }}
            </Link>
        </template>
    </div>
</template>
