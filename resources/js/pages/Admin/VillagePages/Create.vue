<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import VillagePageForm from './Components/VillagePageForm.vue';

defineOptions({ layout: AdminLayout });

defineProps<{
    statuses: string[];
    pageTypes: string[];
}>();
</script>

<template>
    <Head title="Tambah Halaman Desa" />

    <div class="space-y-6">
        <div class="flex items-center gap-4">
            <Button variant="ghost" size="icon" as-child>
                <Link :href="route('admin.village-pages.index')">
                    <ArrowLeftIcon class="w-4 h-4" />
                    <span class="sr-only">Kembali</span>
                </Link>
            </Button>
            <div>
                <h2 class="text-2xl font-bold tracking-tight">Tambah Halaman Desa</h2>
                <p class="text-muted-foreground">Buat profil atau informasi desa baru.</p>
            </div>
        </div>

        <VillagePageForm
            :statuses="statuses"
            :page-types="pageTypes"
        />
    </div>
</template>
