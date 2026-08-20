<script setup lang="ts">
import { DownloadIcon, FileIcon } from 'lucide-vue-next';
import { DocumentService } from '@/services/document';
import type { Document } from '@/types/document';

interface Props {
    document: Document;
}

defineProps<Props>();

const handleDownload = (ulid: string) => {
    window.location.href = DocumentService.downloadUrl(ulid);
};

const formatBytes = (bytes: number, decimals = 2) => {
    if (!+bytes) {
return '0 Bytes'
}

    const k = 1024
    const dm = decimals < 0 ? 0 : decimals
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB']

    const i = Math.floor(Math.log(bytes) / Math.log(k))

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
};
</script>

<template>
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group flex flex-col h-full">
        <div class="p-5 flex-grow">
            <div class="flex items-start gap-4">
                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 rounded-lg flex-shrink-0 group-hover:bg-blue-100 dark:group-hover:bg-blue-900/40 transition-colors">
                    <FileIcon class="w-6 h-6" />
                </div>
                
                <div class="flex-grow min-w-0">
                    <div class="flex items-center gap-2 mb-1">
                        <span v-if="document.category" class="text-xs font-medium px-2 py-1 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                            {{ document.category.name }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            {{ new Date(document.published_at || '').toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                        </span>
                    </div>
                    
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white line-clamp-2 mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                        {{ document.title }}
                    </h3>
                    
                    <p v-if="document.description" class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2 mb-4">
                        {{ document.description }}
                    </p>
                    
                    <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="font-medium uppercase">{{ document.extension }}</span>
                        <span>&bull;</span>
                        <span>{{ formatBytes(document.file_size) }}</span>
                        <span>&bull;</span>
                        <span>{{ document.download_count }} kali diunduh</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="px-5 py-3 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 mt-auto">
            <button 
                @click="handleDownload(document.ulid)"
                class="w-full flex items-center justify-center gap-2 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors py-1.5"
            >
                <DownloadIcon class="w-4 h-4" />
                <span>Unduh Dokumen</span>
            </button>
        </div>
    </div>
</template>
