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
    articles: {
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
    const params: Record<string, string> = {};
    if (form.value.cari && form.value.cari.trim() !== '') {
        params.cari = form.value.cari.trim();
    }
    if (form.value.kategori && form.value.kategori.trim() !== '' && form.value.kategori !== 'all' && form.value.kategori !== 'semua') {
        params.kategori = form.value.kategori.trim();
    }

    router.get(
        route('articles.index'),
        params,
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};

const getArticleImage = (item: any): string | null => {
    if (!item) return null;
    if (item.cover_image_url) return item.cover_image_url;
    if (item.featured_image_url) return item.featured_image_url;
    if (item.featured_image_path) {
        return item.featured_image_path.startsWith('http')
            ? item.featured_image_path
            : `/storage/${item.featured_image_path.replace(/^\//, '')}`;
    }
    return null;
};
</script>

<template>
    <Head title="Kabar Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner pageKey="articles" title="Kabar Desa" subtitle="Informasi, berita, dan artikel terbaru seputar kegiatan di Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Kabar Desa' }]" />
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
                        placeholder="Cari artikel..."
                    />
                </div>
            </div>

            <div v-if="articles.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div v-for="article in articles.data" :key="article.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow group">
                        <div class="h-56 relative overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img v-if="getArticleImage(article)" :src="getArticleImage(article)!" :alt="article.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center"><component :is="Icons.ImageIcon" class="w-12 h-12 text-gray-400" /></div>
                        </div>
                        <div class="p-6 flex flex-col flex-grow">
                            <div class="flex items-center justify-between mb-3">
                                <span v-if="article.category" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                    {{ article.category.name }}
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(article.published_at) }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                <Link :href="route('articles.show', article.slug)">{{ article.title }}</Link>
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-3 mb-4 flex-grow">{{ article.excerpt }}</p>
                            
                            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-100 dark:border-gray-700">
                                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                    <component :is="Icons.UserIcon" class="w-4 h-4 mr-1" />
                                    {{ article.author?.name || 'Admin' }}
                                </div>
                                <Link :href="route('articles.show', article.slug)" class="text-sm font-medium text-blue-600 dark:text-blue-400 flex items-center">
                                    Baca <component :is="Icons.ChevronRightIcon" class="w-4 h-4 ml-1" />
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="articles.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in articles.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === articles.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === articles.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Artikel Tidak Ditemukan" 
                message="Maaf, tidak ada artikel yang sesuai dengan kriteria pencarian Anda." 
                :icon="Icons.FileTextIcon" 
            />
        </div>
    </PublicLayout>
</template>

