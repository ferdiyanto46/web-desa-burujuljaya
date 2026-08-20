<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { 
    Search as MagnifyingGlassIcon, 
    ChevronDown as ChevronDownIcon,
    ChevronUp as ChevronUpIcon
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import PublicLayout from '@/layouts/PublicLayout.vue';
import type { PaginatedData } from '@/types';
import type { Faq } from '@/types/faq';

const props = defineProps<{
    faqs: PaginatedData<Faq>;
    categories: string[];
    filters: {
        search?: string;
        category?: string;
    };
}>();

const search = ref(props.filters.search || '');
const selectedCategory = ref(props.filters.category || '');
const expandedFaqs = ref<Set<number>>(new Set());

watch([search, selectedCategory], ([newSearch, newCategory]) => {
    router.get(
        route('faq.index'),
        { 
            search: newSearch,
            category: newCategory
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
});

const toggleFaq = (id: number) => {
    if (expandedFaqs.value.has(id)) {
        expandedFaqs.value.delete(id);
    } else {
        expandedFaqs.value.add(id);
    }
};

const selectCategory = (category: string) => {
    if (selectedCategory.value === category) {
        selectedCategory.value = '';
    } else {
        selectedCategory.value = category;
    }
};
</script>

<template>
    <Head title="FAQ - Pertanyaan yang Sering Diajukan" />

    <PublicLayout>
        <!-- Hero Section -->
        <section class="bg-primary-900 text-white py-16 md:py-24 relative overflow-hidden">
            <div class="absolute inset-0 z-0">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-800 to-primary-900 opacity-90"></div>
                <!-- Optional pattern overlay here -->
            </div>
            
            <div class="container mx-auto px-4 relative z-10 text-center">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">FAQ</h1>
                <p class="text-lg md:text-xl text-primary-100 max-w-2xl mx-auto mb-8">
                    Pertanyaan yang Sering Diajukan. Temukan jawaban cepat untuk pertanyaan umum seputar layanan dan informasi desa kami.
                </p>

                <!-- Search Bar -->
                <div class="max-w-xl mx-auto relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <MagnifyingGlassIcon class="h-5 w-5 text-gray-400" />
                    </div>
                    <input
                        v-model="search"
                        type="text"
                        class="block w-full pl-10 pr-3 py-3 border-transparent rounded-full leading-5 bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition duration-150 ease-in-out sm:text-sm shadow-lg"
                        placeholder="Cari pertanyaan..."
                    />
                </div>
            </div>
        </section>

        <!-- Main Content Section -->
        <section class="py-16 bg-gray-50 min-h-screen">
            <div class="container mx-auto px-4 max-w-5xl">
                
                <!-- Category Filter -->
                <div v-if="categories.length > 0" class="mb-8 overflow-x-auto pb-4">
                    <div class="flex flex-nowrap md:flex-wrap gap-2">
                        <button
                            @click="selectCategory('')"
                            :class="[
                                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200',
                                selectedCategory === '' 
                                    ? 'bg-primary-600 text-white shadow-md' 
                                    : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                            ]"
                        >
                            Semua
                        </button>
                        <button
                            v-for="category in categories"
                            :key="category"
                            @click="selectCategory(category)"
                            :class="[
                                'px-4 py-2 rounded-full text-sm font-medium whitespace-nowrap transition-colors duration-200',
                                selectedCategory === category 
                                    ? 'bg-primary-600 text-white shadow-md' 
                                    : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200'
                            ]"
                        >
                            {{ category }}
                        </button>
                    </div>
                </div>

                <!-- FAQ List -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                    <div v-if="faqs.data.length === 0" class="p-12 text-center text-gray-500">
                        <MagnifyingGlassIcon class="w-12 h-12 mx-auto mb-4 text-gray-300" />
                        <p class="text-lg">Tidak ada FAQ yang ditemukan.</p>
                        <button 
                            v-if="search || selectedCategory" 
                            @click="search = ''; selectedCategory = '';"
                            class="text-primary-600 hover:text-primary-700 mt-2 font-medium"
                        >
                            Hapus filter
                        </button>
                    </div>

                    <div v-else class="divide-y divide-gray-100">
                        <div 
                            v-for="faq in faqs.data" 
                            :key="faq.id"
                            class="group transition-colors duration-150"
                            :class="{ 'bg-gray-50': expandedFaqs.has(faq.id) }"
                        >
                            <button 
                                @click="toggleFaq(faq.id)"
                                class="w-full text-left px-6 py-5 focus:outline-none flex justify-between items-center gap-4"
                            >
                                <div class="flex items-start gap-4">
                                    <div class="flex-shrink-0 mt-1">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-bold">
                                            Q
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                            {{ faq.question }}
                                        </h3>
                                        <span v-if="faq.category" class="inline-block mt-1 text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ faq.category }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 text-gray-400">
                                    <ChevronUpIcon v-if="expandedFaqs.has(faq.id)" class="w-6 h-6" />
                                    <ChevronDownIcon v-else class="w-6 h-6 group-hover:text-primary-500" />
                                </div>
                            </button>
                            
                            <div 
                                v-show="expandedFaqs.has(faq.id)" 
                                class="px-6 pb-6 pt-0 ml-12 text-gray-700 prose max-w-none prose-p:leading-relaxed prose-a:text-primary-600"
                            >
                                <div class="pl-4 border-l-2 border-primary-200">
                                    <div v-html="faq.answer"></div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-100 flex justify-end">
                                    <a :href="route('faq.show', faq.slug)" class="text-sm font-medium text-primary-600 hover:text-primary-800 flex items-center gap-1">
                                        Tautan Langsung <span aria-hidden="true">&rarr;</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div v-if="faqs.meta && faqs.meta.last_page > 1" class="mt-10 flex justify-center">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, key) in faqs.meta.links"
                            :key="key"
                            :href="link.url"
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                            :class="[
                                link.active 
                                    ? 'z-10 bg-primary-50 border-primary-500 text-primary-600' 
                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                { 'rounded-l-md': key === 0, 'rounded-r-md': key === faqs.meta.links.length - 1 },
                                { 'cursor-not-allowed': !link.url }
                            ]"
                            v-html="link.label"
                        />
                    </nav>
                </div>
                
            </div>
        </section>
    </PublicLayout>
</template>
