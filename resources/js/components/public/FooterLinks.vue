<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Informasi Desa</h3>
            <ul class="space-y-3">
                <li><Link :href="route('profile.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Profil Desa</Link></li>
                <li><Link :href="route('articles.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Berita & Artikel</Link></li>
                <li><Link :href="route('gallery.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Galeri Foto</Link></li>
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white uppercase tracking-wider mb-4">Layanan & Potensi</h3>
            <ul class="space-y-3">
                <li><Link :href="route('complaints.create')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Pengaduan Masyarakat</Link></li>
                <li><Link :href="route('documents.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">Dokumen Publik</Link></li>
                <li><Link :href="route('umkm.index')" class="text-base text-gray-600 dark:text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 transition-colors">UMKM Warga</Link></li>
            </ul>
        </div>
    </div>
</template>
