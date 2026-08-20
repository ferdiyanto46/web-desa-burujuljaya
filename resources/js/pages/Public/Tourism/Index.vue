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
    tourisms: {
        data: any[];
        links: any[];
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
        route('tourism.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);
</script>

<template>
    <Head title="Destinasi Wisata" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Destinasi Wisata" subtitle="Jelajahi keindahan alam, budaya, dan pesona wisata di Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Wisata' }]" />
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
                        placeholder="Cari destinasi wisata..."
                    />
                </div>
            </div>

            <div v-if="tourisms.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div 
                        v-for="tourism in tourisms.data" 
                        :key="tourism.id" 
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group"
                    >
                        <div class="relative h-64 overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img v-if="tourism.featured_image_url" :src="tourism.featured_image_url" :alt="tourism.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                            <div v-else class="absolute inset-0 flex items-center justify-center"><component :is="Icons.MapIcon" class="w-12 h-12 text-gray-400" /></div>
                            
                            <div class="absolute top-4 left-4 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm px-3 py-1.5 rounded-full flex items-center text-xs font-bold text-blue-600 dark:text-blue-400 shadow-sm">
                                {{ tourism.category?.name || 'Wisata' }}
                            </div>
                        </div>
                        
                        <div class="p-6 md:p-8 flex-grow flex flex-col">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <Link :href="route('tourism.show', tourism.slug)">{{ tourism.title }}</Link>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 flex items-start gap-1.5 mb-4 line-clamp-2 h-10">
                                <component :is="Icons.MapIcon" class="w-4 h-4 flex-shrink-0 mt-0.5 text-red-500" />
                                {{ tourism.address }}
                            </p>
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-6 flex-grow">{{ tourism.description }}</p>
                            
                            <div class="mt-auto">
                                <Link :href="route('tourism.show', tourism.slug)" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                    Lihat Detail
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="tourisms.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in tourisms.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
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
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
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
                :icon="Icons.MapIcon" 
            />
        </div>
    </PublicLayout>
</template>
