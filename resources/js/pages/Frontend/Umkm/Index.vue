<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import { MapPinIcon, StoreIcon } from 'lucide-vue-next'
import { ref } from 'vue'
import Breadcrumb from '@/components/public/Breadcrumb.vue'
import CategoryFilter from '@/components/public/CategoryFilter.vue'
import EmptySection from '@/components/public/EmptySection.vue'
import PageBanner from '@/components/public/PageBanner.vue'
import SearchBar from '@/components/public/SearchBar.vue'
import PublicLayout from '@/layouts/PublicLayout.vue'
import { route } from '@/lib/route'
import type { PaginationData } from '@/types'
import type { Umkm, UmkmCategory } from '@/types/umkm'

const props = defineProps<{
    umkms: PaginationData<Umkm>
    categories: UmkmCategory[]
    filters: {
        kategori?: string
        cari?: string
    }
}>()

const categoryOptions = props.categories.map(c => ({ label: c.name, value: c.slug }))

const form = ref({
    cari: props.filters?.cari || '',
    kategori: props.filters?.kategori || '',
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
    const params: Record<string, string> = {}
    if (form.value.cari && form.value.cari.trim() !== '') {
        params.cari = form.value.cari.trim()
    }
    if (form.value.kategori && form.value.kategori.trim() !== '' && form.value.kategori !== 'all' && form.value.kategori !== 'semua') {
        params.kategori = form.value.kategori.trim()
    }

    router.get(
        route('umkm.index'),
        params,
        { preserveState: true, preserveScroll: true, replace: true }
    )
}, 300)

const stripHtml = (html?: string | null): string => {
    if (!html) return '';
    const formatted = html.replace(/<br\s*[\/]?>/gi, ' ').replace(/<\/p>/gi, ' ');
    const text = formatted.replace(/<[^>]*>/g, '');
    return text.replace(/&nbsp;/g, ' ').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/\s+/g, ' ').trim();
};

const getUmkmImage = (item: Umkm): string | null => {
    if (!item?.featured_image_path) return null;
    return item.featured_image_path.startsWith('http')
        ? item.featured_image_path
        : `/storage/${item.featured_image_path.replace(/^\//, '')}`;
};
</script>

<template>
    <Head title="UMKM Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner 
                pageKey="umkm"
                title="Produk UMKM Desa" 
                subtitle="Dukung dan temukan berbagai produk unggulan dari Usaha Mikro, Kecil, dan Menengah desa kami" 
            />
        </template>

        <div class="py-12 bg-slate-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <Breadcrumb :items="[{ name: 'UMKM' }]" />

                <!-- Filters -->
                <div class="mt-8 flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div class="w-full md:w-96">
                        <SearchBar 
                            :modelValue="form.cari"
                            placeholder="Cari UMKM atau produk..."
                            @update:modelValue="handleSearch"
                        />
                    </div>
                    <div class="w-full md:w-auto overflow-x-auto">
                        <CategoryFilter
                            :categories="categoryOptions"
                            :modelValue="form.kategori"
                            @update:modelValue="handleCategoryChange"
                        />
                    </div>
                </div>

                <!-- List -->
                <div v-if="umkms?.data && umkms.data.length > 0" class="mt-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div 
                            v-for="umkm in umkms.data" 
                            :key="umkm.id"
                            class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md border border-gray-100 dark:border-gray-700 overflow-hidden transition-all duration-300 transform hover:-translate-y-1 flex flex-col h-full group"
                        >
                            <div class="aspect-[4/3] relative overflow-hidden bg-gray-100 dark:bg-gray-700">
                                <img 
                                    v-if="getUmkmImage(umkm)" 
                                    :src="getUmkmImage(umkm)!" 
                                    :alt="umkm.title" 
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                />
                                <div v-else class="absolute inset-0 flex items-center justify-center">
                                    <StoreIcon class="w-12 h-12 text-gray-300 dark:text-gray-600" />
                                </div>
                            </div>

                            <div class="p-5 flex-1 flex flex-col">
                                <div class="flex items-center gap-2 mb-2">
                                    <span v-if="umkm.category" class="bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-xs font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ umkm.category.name }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-green-600 dark:group-hover:text-green-400 transition-colors">
                                    <Link :href="route('umkm.show', umkm.slug)">{{ umkm.title }}</Link>
                                </h3>
                                
                                <div v-if="umkm.address" class="flex items-start gap-1 text-sm text-gray-500 dark:text-gray-400 mb-3">
                                    <MapPinIcon class="w-4 h-4 flex-shrink-0 mt-0.5" />
                                    <span class="line-clamp-1">{{ umkm.address }}</span>
                                </div>

                                <p class="text-gray-600 dark:text-gray-300 text-sm line-clamp-2 flex-grow">{{ stripHtml(umkm.description) }}</p>
                                
                                <div class="mt-6 flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                                    <span v-if="umkm.owner_name" class="text-sm text-gray-500 dark:text-gray-400 font-medium">
                                        Milik: {{ umkm.owner_name }}
                                    </span>
                                    <Link :href="route('umkm.show', umkm.slug)" class="text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 flex items-center ml-auto">
                                        Kunjungi <span class="ml-1">&rarr;</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div v-if="umkms.links && umkms.links.length > 3" class="flex justify-center mt-8">
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <template v-for="(link, i) in umkms.links" :key="i">
                                <Link 
                                    v-if="link.url"
                                    :href="link.url"
                                    v-html="link.label"
                                    :class="[
                                        link.active ? 'z-10 bg-green-50 dark:bg-green-900/30 border-green-500 text-green-600 dark:text-green-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                        i === 0 ? 'rounded-l-md' : '',
                                        i === umkms.links.length - 1 ? 'rounded-r-md' : ''
                                    ]"
                                />
                                <span 
                                    v-else 
                                    v-html="link.label" 
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                    :class="[
                                        i === 0 ? 'rounded-l-md' : '',
                                        i === umkms.links.length - 1 ? 'rounded-r-md' : ''
                                    ]"
                                />
                            </template>
                        </nav>
                    </div>
                </div>
                
                <EmptySection 
                    v-else 
                    title="UMKM Tidak Ditemukan" 
                    message="Maaf, tidak ada data UMKM yang sesuai dengan kriteria pencarian Anda saat ini." 
                    class="mt-8"
                />
            </div>
        </div>
    </PublicLayout>
</template>
