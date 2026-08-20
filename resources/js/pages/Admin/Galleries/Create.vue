<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft as ArrowLeftIcon } from 'lucide-vue-next'
import GalleryForm from '@/components/galleries/GalleryForm.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'

defineOptions({ layout: AdminLayout })
</script>

<template>
    <Head title="Tambah Galeri Baru" />

    <div class="space-y-6 max-w-5xl mx-auto">
        <div class="flex items-center gap-4">
            <Link 
                :href="route('admin.galleries.index')" 
                class="p-2 rounded-lg text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-900 dark:hover:text-white transition-colors"
                title="Kembali ke daftar"
            >
                <ArrowLeftIcon class="w-5 h-5" />
            </Link>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Tambah Galeri Baru</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Buat album galeri baru untuk menyimpan dokumentasi foto.</p>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <GalleryForm />
        </div>
    </div>
</template>
