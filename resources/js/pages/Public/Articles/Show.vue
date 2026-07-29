<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    article: any;
    relatedArticles: Array<any>;
}>();

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};


</script>

<template>
    <Head :title="article.title" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Kabar Desa', route: 'public.articles.index' }, { name: article.title }]" />
        </template>
        
        <div class="py-8 lg:py-12">
            <div class="flex flex-col lg:flex-row gap-12">
                
                <!-- Main Content -->
                <article class="lg:w-2/3">
                    <div class="mb-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span v-if="article.category" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                                {{ article.category.name }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                <component :is="Icons.CalendarIcon" class="w-4 h-4 mr-1" />
                                {{ formatDate(article.published_at) }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                <component :is="Icons.EyeIcon" class="w-4 h-4 mr-1" />
                                {{ article.views }} kali dibaca
                            </span>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-6">
                            {{ article.title }}
                        </h1>
                        
                        <div class="flex items-center gap-4 mb-8 pb-8 border-b border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500 overflow-hidden">
                                <component :is="Icons.UserIcon" class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ article.author?.name || 'Admin Desa' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Penulis</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="article.cover_image_url" class="rounded-3xl overflow-hidden mb-10 shadow-md">
                        <img :src="article.cover_image_url" :alt="article.title" class="w-full h-auto object-cover max-h-[500px]" />
                    </div>

                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mb-12">
                        <div v-html="article.content"></div>
                    </div>

                    <!-- Share & Tags -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 border-t border-b border-gray-100 dark:border-gray-700 gap-4 mb-12">
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bagikan:</span>
                            <ShareButtons :title="article.title" />
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="lg:w-1/3 space-y-8">
                    <!-- Related Articles -->
                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-2xl p-6 border border-gray-100 dark:border-gray-700">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 pb-4 border-b border-gray-200 dark:border-gray-700 flex items-center">
                            <component :is="Icons.FileTextIcon" class="w-5 h-5 mr-2 text-blue-500" />
                            Artikel Terkait
                        </h3>
                        
                        <div v-if="relatedArticles?.length > 0" class="space-y-6">
                            <Link v-for="related in relatedArticles" :key="related.id" :href="route('articles.show', related.slug)" class="flex gap-4 group">
                                <div class="w-20 h-20 flex-shrink-0 rounded-lg overflow-hidden bg-gray-200 dark:bg-gray-700">
                                    <img v-if="related.cover_image_url" :src="related.cover_image_url" :alt="related.title" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                    <div v-else class="w-full h-full flex items-center justify-center"><component :is="Icons.ImageIcon" class="w-6 h-6 text-gray-400" /></div>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors line-clamp-2 mb-1">{{ related.title }}</h4>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ formatDate(related.published_at) }}</span>
                                </div>
                            </Link>
                        </div>
                        <div v-else class="text-sm text-gray-500 italic">
                            Tidak ada artikel terkait.
                        </div>
                    </div>
                </aside>
                
            </div>
        </div>
    </PublicLayout>
</template>
