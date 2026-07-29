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
    umkms: {
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
        route('umkm.index'),
        { cari: form.value.cari, kategori: form.value.kategori },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);
</script>

<template>
    <Head title="Direktori UMKM" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="UMKM Desa" subtitle="Dukung pertumbuhan ekonomi lokal dengan menjelajahi direktori Usaha Mikro, Kecil, dan Menengah Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'UMKM' }]" />
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
                        placeholder="Cari UMKM..."
                    />
                </div>
            </div>

            <div v-if="umkms.data.length > 0">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <Link 
                        v-for="umkm in umkms.data" 
                        :key="umkm.id" 
                        :href="route('umkm.show', umkm.slug)"
                        class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 flex flex-col hover:shadow-lg hover:-translate-y-1 transition-all duration-300 group"
                    >
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-20 h-20 rounded-2xl bg-gray-100 dark:bg-gray-700 flex items-center justify-center overflow-hidden flex-shrink-0 shadow-inner">
                                <img v-if="umkm.logo_url" :src="umkm.logo_url" :alt="umkm.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" />
                                <component v-else :is="Icons.StoreIcon" class="w-8 h-8 text-gray-400" />
                            </div>
                            <div>
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 mb-1 border border-indigo-100 dark:border-indigo-800">
                                    {{ umkm.category?.name || 'UMKM' }}
                                </span>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors line-clamp-1">
                                    {{ umkm.name }}
                                </h3>
                            </div>
                        </div>
                        
                        <div class="space-y-3 mb-6 flex-grow">
                            <div class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                <component :is="Icons.UserIcon" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5 text-gray-400" />
                                <span class="line-clamp-1">Pemilik: <span class="font-medium text-gray-900 dark:text-gray-200">{{ umkm.owner_name }}</span></span>
                            </div>
                            <div class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                <component :is="Icons.MapIcon" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5 text-gray-400" />
                                <span class="line-clamp-2">{{ umkm.address }}</span>
                            </div>
                            <div v-if="umkm.phone" class="flex items-start text-sm text-gray-600 dark:text-gray-400">
                                <component :is="Icons.PhoneIcon" class="w-4 h-4 mr-2 flex-shrink-0 mt-0.5 text-gray-400" />
                                <span>{{ umkm.phone }}</span>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center mt-auto">
                            <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Kunjungi Toko</span>
                            <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-900/30 flex items-center justify-center text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                <component :is="Icons.ChevronRightIcon" class="w-4 h-4" />
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="umkms.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in umkms.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-indigo-50 dark:bg-indigo-900/30 border-indigo-500 text-indigo-600 dark:text-indigo-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === umkms.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
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
                message="Maaf, tidak ada UMKM yang sesuai dengan pencarian Anda." 
                :icon="Icons.StoreIcon" 
            />
        </div>
    </PublicLayout>
</template>
