<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Informasi</h3>
            <ul class="space-y-3">
                <li><Link :href="route('pages.profile')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Profil Desa</Link></li>
                <li><Link :href="route('articles.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Berita & Artikel</Link></li>
                <li><Link :href="route('announcements.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Pengumuman</Link></li>
                <li><Link :href="route('agendas.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Agenda Kegiatan</Link></li>
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Layanan Publik</h3>
            <ul class="space-y-3">
                <li><Link :href="route('complaints.create')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Pengaduan Masyarakat</Link></li>
                <li><Link :href="route('documents.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Dokumen Publik</Link></li>
                <li><Link :href="route('tourism.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Potensi Wisata</Link></li>
                <li><Link :href="route('products.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">Produk Desa</Link></li>
            </ul>
        </div>
    </div>
</template>
