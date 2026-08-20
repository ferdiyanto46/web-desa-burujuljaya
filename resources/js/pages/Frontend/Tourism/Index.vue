<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import CategoryFilter from '@/components/public/CategoryFilter.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import SearchBar from '@/components/public/SearchBar.vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';
import type { PaginationData, Tourism, TourismCategory } from '@/types';

const props = defineProps<{
    tourisms: PaginationData<Tourism>;
    categories: TourismCategory[];
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
        route('tourism.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);
</script>

<template>
    <Head title="Pariwisata" />

    <PublicLayout>
        <template #banner>
            <PageBanner pageKey="tourism" title="Pariwisata Desa" subtitle="Jelajahi keindahan dan destinasi wisata menarik di desa kami" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pariwisata' }]" />
        </template>
        
        <div class="py-8">
            <div class="flex flex-col md:flex-row justify-between gap-4 mb-8">
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
                        placeholder="Cari destinasi wisata..."
                    />
                </div>
            </div>

            <div v-if="tourisms.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div v-for="tourism in tourisms.data" :key="tourism.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow group">
                        <div class="h-56 relative overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img v-if="tourism.featured_image_url" :src="tourism.featured_image_url" :alt="tourism.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                            <div v-if="tourism.is_pinned" class="absolute top-4 right-4 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full shadow">
                                Unggulan
                            </div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center justify-between mb-3">
                                <span v-if="tourism.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                    {{ tourism.category.name }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <Link :href="route('tourism.show', tourism.slug)">{{ tourism.title }}</Link>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4 flex-grow">{{ tourism.excerpt }}</p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                <Link :href="route('tourism.show', tourism.slug)" class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center">
                                    Lihat Detail <span class="ml-1">&rarr;</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="tourisms.links && tourisms.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in tourisms.links" :key="i">
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === tourisms.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === tourisms.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Destinasi Tidak Ditemukan" 
                message="Maaf, tidak ada destinasi wisata yang sesuai dengan kriteria pencarian Anda." 
            />
        </div>
    </PublicLayout>
</template>
