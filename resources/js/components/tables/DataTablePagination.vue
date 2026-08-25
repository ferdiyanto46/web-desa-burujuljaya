<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { PaginationData, PaginationLink } from '@/types';

interface Props {
    pagination: PaginationData<any>;
}

defineProps<Props>();

const cleanLinks = (links: PaginationLink[]) => {
    if (!links || !Array.isArray(links)) return [];
    return links.filter(link => {
        const label = (link.label || '').toLowerCase();
        return !label.includes('previous') && 
               !label.includes('next') && 
               !label.includes('pagination.previous') && 
               !label.includes('pagination.next') && 
               !label.includes('sebelumnya') && 
               !label.includes('selanjutnya') && 
               !label.includes('&lsaquo;') && 
               !label.includes('&rsaquo;') && 
               !label.includes('&laquo;') && 
               !label.includes('&raquo;');
    });
};
</script>

<template>
    <div v-if="pagination.total > 0" class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6 dark:border-gray-700 dark:bg-gray-900 mt-4 rounded-lg shadow-sm">
        <div class="flex flex-1 justify-between sm:hidden">
            <component 
                :is="pagination.prev_page_url ? Link : 'span'"
                :href="pagination.prev_page_url" 
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                :class="[!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed' : '']"
            >
                Sebelumnya
            </component>
            <component 
                :is="pagination.next_page_url ? Link : 'span'"
                :href="pagination.next_page_url" 
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                :class="[!pagination.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
            >
                Selanjutnya
            </component>
        </div>
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-400">
                    Menampilkan
                    <span class="font-medium">{{ pagination.from || 0 }}</span>
                    sampai
                    <span class="font-medium">{{ pagination.to || 0 }}</span>
                    dari
                    <span class="font-medium">{{ pagination.total }}</span>
                    hasil
                </p>
            </div>
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <component 
                        :is="pagination.prev_page_url ? Link : 'span'"
                        :href="pagination.prev_page_url" 
                        class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:ring-gray-700 dark:hover:bg-gray-800"
                        :class="[!pagination.prev_page_url ? 'opacity-50 cursor-not-allowed' : '']"
                        title="Sebelumnya"
                    >
                        <span class="sr-only">Sebelumnya</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                        </svg>
                    </component>
                    
                    <component 
                        :is="link.url ? Link : 'span'"
                        v-for="(link, idx) in cleanLinks(pagination.links)" 
                        :key="idx"
                        :href="link.url"
                        class="relative inline-flex items-center px-4 py-2 text-sm font-semibold focus:z-20"
                        :class="[
                            link.active 
                                ? 'z-10 bg-blue-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600' 
                                : 'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0 dark:text-gray-300 dark:ring-gray-700 dark:hover:bg-gray-800',
                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                        ]"
                    >
                        <span v-html="link.label"></span>
                    </component>

                    <component 
                        :is="pagination.next_page_url ? Link : 'span'"
                        :href="pagination.next_page_url" 
                        class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 dark:ring-gray-700 dark:hover:bg-gray-800"
                        :class="[!pagination.next_page_url ? 'opacity-50 cursor-not-allowed' : '']"
                        title="Selanjutnya"
                    >
                        <span class="sr-only">Selanjutnya</span>
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </component>
                </nav>
            </div>
        </div>
    </div>
</template>
