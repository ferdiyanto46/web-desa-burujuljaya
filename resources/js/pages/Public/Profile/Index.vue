<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
</script>

<template>
    <Head title="Profil Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Profil Desa" />
        </template>
        
        <div class="py-12">
            <EmptySection 
                title="Halaman Sedang Dibangun" 
                message="Konten untuk halaman Profil Desa ini sedang dipersiapkan dan akan segera hadir." 
            />
        </div>
    </PublicLayout>
</template>
