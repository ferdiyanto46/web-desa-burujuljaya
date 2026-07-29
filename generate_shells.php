<?php
$shells = [
    'resources/js/pages/Public/Profile/Index.vue' => 'Profil Desa',
    'resources/js/pages/Public/Articles/Index.vue' => 'Daftar Artikel',
    'resources/js/pages/Public/Articles/Show.vue' => 'Detail Artikel',
    'resources/js/pages/Public/Announcements/Index.vue' => 'Daftar Pengumuman',
    'resources/js/pages/Public/Announcements/Show.vue' => 'Detail Pengumuman',
    'resources/js/pages/Public/Agendas/Index.vue' => 'Daftar Agenda',
    'resources/js/pages/Public/Agendas/Show.vue' => 'Detail Agenda',
    'resources/js/pages/Public/Gallery/Index.vue' => 'Album Galeri',
    'resources/js/pages/Public/Gallery/Show.vue' => 'Detail Album',
    'resources/js/pages/Public/Videos/Index.vue' => 'Galeri Video',
    'resources/js/pages/Public/Tourism/Index.vue' => 'Daftar Wisata',
    'resources/js/pages/Public/Tourism/Show.vue' => 'Detail Wisata',
    'resources/js/pages/Public/Umkm/Index.vue' => 'Daftar UMKM',
    'resources/js/pages/Public/Umkm/Show.vue' => 'Detail UMKM',
    'resources/js/pages/Public/Products/Index.vue' => 'Daftar Produk',
    'resources/js/pages/Public/Products/Show.vue' => 'Detail Produk',
    'resources/js/pages/Public/Documents/Index.vue' => 'Daftar Dokumen',
    'resources/js/pages/Public/Complaints/Create.vue' => 'Buat Pengaduan',
    'resources/js/pages/Public/Complaints/Track.vue' => 'Lacak Pengaduan',
    'resources/js/pages/Public/Pages/Profile.vue' => 'Profil Desa',
    'resources/js/pages/Public/Pages/VisionMission.vue' => 'Visi Misi',
    'resources/js/pages/Public/Pages/Apparatus.vue' => 'Aparatur Desa',
];

// Create dirs if not exist
foreach ($shells as $path => $title) {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

foreach ($shells as $path => $title) {
    $content = <<<VUE
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/layouts/PublicLayout.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import EmptySection from '@/components/public/EmptySection.vue';
</script>

<template>
    <Head title="{$title}" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="{$title}" />
        </template>
        
        <div class="py-12">
            <EmptySection 
                title="Halaman Sedang Dibangun" 
                message="Konten untuk halaman {$title} ini sedang dipersiapkan dan akan segera hadir." 
            />
        </div>
    </PublicLayout>
</template>
VUE;

    if(!file_exists($path)) {
        file_put_contents($path, $content);
        echo "Created $path\n";
    }
}
