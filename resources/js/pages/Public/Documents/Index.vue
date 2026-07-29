<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import CategoryFilter from '@/components/public/CategoryFilter.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import SearchBar from '@/components/public/SearchBar.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';

const props = defineProps<{
    documents: {
        data: Array<any>;
        links: Array<any>;
        current_page: number;
        last_page: number;
        total: number;
    };
    categories: Array<{ id: number; name: string; slug: string }>;
    filters: {
        kategori?: string;
        cari?: string;
    };
}>();

const categoryOptions = props.categories.map(c => ({ label: c.name, value: c.slug }));

const form = ref({
    cari: props.filters.cari || '',
    kategori: props.filters.kategori || '',
});

const handleSearch = (value: string) => {
    form.value.cari = value;
    submit();
};

const handleCategoryChange = (value: string) => {
    form.value.kategori = value;
    submit();
};

const submit = debounce(() => {
    router.get(
        route('documents.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

const getFileIcon = (extension: string) => {
    const ext = extension?.toLowerCase() || '';

    if (['pdf'].includes(ext)) {
return 'text-red-500 bg-red-50 dark:bg-red-900/30';
}

    if (['doc', 'docx'].includes(ext)) {
return 'text-blue-500 bg-blue-50 dark:bg-blue-900/30';
}

    if (['xls', 'xlsx'].includes(ext)) {
return 'text-green-500 bg-green-50 dark:bg-green-900/30';
}

    if (['ppt', 'pptx'].includes(ext)) {
return 'text-orange-500 bg-orange-50 dark:bg-orange-900/30';
}

    if (['zip', 'rar'].includes(ext)) {
return 'text-purple-500 bg-purple-50 dark:bg-purple-900/30';
}

    return 'text-gray-500 bg-gray-100 dark:bg-gray-800';
};

const formatSize = (bytes: number) => {
    if (bytes === 0) {
return '0 Bytes';
}

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
};

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'short', year: 'numeric'
    }).format(new Date(dateString));
};
</script>

<template>
    <Head title="Dokumen Publik" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Dokumen Publik" subtitle="Pusat unduhan dokumen resmi, formulir, dan regulasi Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Dokumen' }]" />
        </template>
        
        <div class="py-8 max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-10">
                <div class="w-full md:w-2/3">
                    <CategoryFilter 
                        :categories="categoryOptions"
                        :modelValue="form.kategori"
                        @update:modelValue="handleCategoryChange"
                    />
                </div>
                <div class="w-full md:w-1/3">
                    <SearchBar 
                        :modelValue="form.cari"
                        @update:modelValue="handleSearch"
                        placeholder="Cari dokumen..."
                    />
                </div>
            </div>

            <div v-if="documents.data.length > 0">
                <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mb-12">
                    <ul class="divide-y divide-gray-100 dark:divide-gray-700">
                        <li 
                            v-for="document in documents.data" 
                            :key="document.id"
                            class="p-6 md:p-8 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors flex flex-col md:flex-row md:items-center gap-6"
                        >
                            <!-- File Icon -->
                            <div class="flex-shrink-0 flex items-center justify-center w-16 h-16 rounded-2xl" :class="getFileIcon(document.file_extension)">
                                <component :is="Icons.FileTextIcon" class="w-8 h-8" />
                            </div>
                            
                            <!-- Info -->
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="px-2.5 py-0.5 rounded-md text-xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600 uppercase">
                                        {{ document.category?.name || 'Umum' }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">{{ document.file_extension }}</span>
                                </div>
                                <h3 class="text-lg md:text-xl font-bold text-gray-900 dark:text-white mb-2">{{ document.title }}</h3>
                                <p v-if="document.description" class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ document.description }}</p>
                                
                                <div class="flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <component :is="Icons.CalendarIcon" class="w-3.5 h-3.5 mr-1" /> {{ formatDate(document.created_at) }}
                                    </span>
                                    <span class="flex items-center" v-if="document.file_size">
                                        <component :is="Icons.DatabaseIcon" class="w-3.5 h-3.5 mr-1" /> {{ formatSize(document.file_size) }}
                                    </span>
                                    <span class="flex items-center">
                                        <component :is="Icons.DownloadIcon" class="w-3.5 h-3.5 mr-1" /> {{ document.download_count }} kali diunduh
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Action -->
                            <div class="flex-shrink-0 mt-4 md:mt-0">
                                <a 
                                    :href="route('documents.download', document.ulid)" 
                                    target="_blank"
                                    class="inline-flex items-center justify-center w-full md:w-auto px-6 py-3 border border-transparent text-sm font-bold rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <component :is="Icons.DownloadIcon" class="w-4 h-4 mr-2" /> Unduh File
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Pagination -->
                <div v-if="documents.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in documents.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === documents.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === documents.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Dokumen Tidak Ditemukan" 
                message="Maaf, tidak ada dokumen yang sesuai dengan pencarian Anda." 
                :icon="Icons.FileTextIcon" 
            />
        </div>
    </PublicLayout>
</template>
