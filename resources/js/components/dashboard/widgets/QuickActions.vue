<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icons } from '@/constants/icons';
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col h-full">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
            <h3 class="text-base font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <component :is="Icons.PlusCircleIcon" class="w-5 h-5 text-indigo-500" />
                Aksi Cepat
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <Link :href="route('admin.articles.create')" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-blue-50 dark:hover:bg-blue-900/20 hover:border-blue-200 dark:hover:border-blue-800 transition-colors group">
                    <component :is="Icons.FileTextIcon" class="w-6 h-6 text-gray-400 group-hover:text-blue-500 mb-2 transition-colors" />
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 group-hover:text-blue-700 dark:group-hover:text-blue-400 text-center">Tulis Artikel</span>
                </Link>
                
                <Link :href="route('admin.umkm.create')" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 hover:border-indigo-200 dark:hover:border-indigo-800 transition-colors group">
                    <component :is="Icons.StoreIcon" class="w-6 h-6 text-gray-400 group-hover:text-indigo-500 mb-2 transition-colors" />
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 group-hover:text-indigo-700 dark:group-hover:text-indigo-400 text-center">Tambah UMKM</span>
                </Link>
                
                <Link :href="route('admin.documents.create')" class="flex flex-col items-center justify-center p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-100 dark:border-gray-700 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 hover:border-emerald-200 dark:hover:border-emerald-800 transition-colors group">
                    <component :is="Icons.FolderIcon" class="w-6 h-6 text-gray-400 group-hover:text-emerald-500 mb-2 transition-colors" />
                    <span class="text-xs font-semibold text-gray-600 dark:text-gray-300 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 text-center">Upload Dokumen</span>
                </Link>
            </div>
        </div>
    </div>
</template>
