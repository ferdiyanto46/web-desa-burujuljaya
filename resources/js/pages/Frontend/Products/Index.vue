<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import { StoreIcon, ShoppingBagIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import Breadcrumb from '@/components/public/Breadcrumb.vue'
import CategoryFilter from '@/components/public/CategoryFilter.vue'
import EmptySection from '@/components/public/EmptySection.vue'
import PageBanner from '@/components/public/PageBanner.vue'
import SearchBar from '@/components/public/SearchBar.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { PaginationData } from '@/types'
import type { Product, ProductCategory } from '@/types/product'

const props = defineProps<{
    products: PaginationData<Product>
    categories: ProductCategory[]
    filters: {
        kategori?: string
        cari?: string
    }
}>()

const categoryOptions = props.categories.map(c => ({ label: c.name, value: c.slug }))

const form = ref({
    cari: props.filters.cari || '',
    kategori: props.filters.kategori || '',
})

const handleSearch = (value: string) => {
    form.value.cari = value
    submit()
}

const handleCategoryChange = (value: string) => {
    form.value.kategori = value
    submit()
}

const submit = debounce(() => {
    router.get(
        route('products.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    )
}, 300)
</script>

<template>
    <Head title="Produk Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner 
                title="Katalog Produk Desa" 
                subtitle="Temukan berbagai produk unggulan dan berkualitas hasil karya UMKM desa kami" 
            />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Produk' }]" />
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
                        placeholder="Cari produk..."
                    />
                </div>
            </div>

            <div v-if="products.data.length > 0">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
                    <div v-for="product in products.data" :key="product.id" class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col hover:shadow-md transition-shadow group">
                        <div class="h-56 relative overflow-hidden bg-gray-200 dark:bg-gray-700">
                            <img v-if="product.featured_image_path" :src="`/storage/${product.featured_image_path}`" :alt="product.title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                            <div v-else class="absolute inset-0 flex items-center justify-center">
                                <ShoppingBagIcon class="w-12 h-12 text-gray-400" />
                            </div>
                        </div>
                        <div class="p-5 flex flex-col flex-grow">
                            <div class="flex items-center justify-between mb-2">
                                <span v-if="product.category" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                    {{ product.category.name }}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                <Link :href="route('products.show', product.slug)">{{ product.title }}</Link>
                            </h3>
                            
                            <div class="text-lg font-bold text-primary mb-3">
                                Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}
                            </div>

                            <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 flex-grow mb-4" v-html="product.description"></p>
                            
                            <div class="mt-auto flex items-center pt-3 border-t border-gray-100 dark:border-gray-700">
                                <StoreIcon class="w-4 h-4 text-gray-400 mr-2" />
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium truncate">
                                    {{ product.umkm?.title || 'UMKM Desa' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="products.links && products.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in products.links" :key="i">
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-primary/10 border-primary text-primary' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === products.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
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
                message="Maaf, tidak ada produk yang sesuai dengan kriteria pencarian Anda saat ini." 
            />
        </div>
    </PublicLayout>
</template>
