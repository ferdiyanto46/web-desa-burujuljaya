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
    products: {
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
        route('products.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);
</script>

<template>
    <Head title="Katalog Produk" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Katalog Produk" subtitle="Temukan dan beli produk-produk unggulan hasil karya warga dan UMKM Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Produk' }]" />
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
                        placeholder="Cari produk..."
                    />
                </div>
            </div>

            <div v-if="products.data.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                    <Link 
                        v-for="product in products.data" 
                        :key="product.id"
                        :href="route('products.show', product.slug)"
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col group hover:shadow-lg hover:-translate-y-1 transition-all duration-300"
                    >
                        <div class="h-56 bg-gray-100 dark:bg-gray-700 relative overflow-hidden">
                            <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center"><component :is="Icons.PackageIcon" class="w-12 h-12 text-gray-400" /></div>
                            
                            <div class="absolute top-3 left-3">
                                <span class="px-2 py-1 bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm rounded-md text-[10px] font-bold text-gray-700 dark:text-gray-300 shadow-sm uppercase tracking-wider">
                                    {{ product.category?.name || 'Umum' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <p v-if="product.umkm" class="text-xs font-medium text-blue-600 dark:text-blue-400 mb-1 flex items-center">
                                <component :is="Icons.StoreIcon" class="w-3 h-3 mr-1" /> {{ product.umkm.name }}
                            </p>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-blue-600 transition-colors">{{ product.name }}</h3>
                            
                            <div class="mt-auto pt-4 flex justify-between items-end border-t border-gray-50 dark:border-gray-700/50">
                                <div class="font-extrabold text-gray-900 dark:text-white text-lg">Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}</div>
                                <div class="w-8 h-8 rounded-full bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-500 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors">
                                    <component :is="Icons.ChevronRightIcon" class="w-4 h-4" />
                                </div>
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="products.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in products.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-blue-50 dark:bg-blue-900/30 border-blue-500 text-blue-600 dark:text-blue-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === products.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === products.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Produk Tidak Ditemukan" 
                message="Maaf, tidak ada produk yang sesuai dengan pencarian Anda." 
                :icon="Icons.PackageIcon" 
            />
        </div>
    </PublicLayout>
</template>
