<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon, CalendarIcon, EyeIcon, TagIcon, UserIcon, EditIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { Article } from '@/types';

interface Props {
    article: Article;
}

defineProps<Props>();
</script>

<template>
    <Head :title="`Preview: ${article.title}`" />

    <DashboardContainer>
        <template #header>
            <div class="flex flex-col">
                <button
                    @click="router.visit(route('admin.articles.index'))"
                    class="mb-4 inline-flex w-fit items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    <ArrowLeftIcon class="mr-1 h-4 w-4" />
                    Kembali ke Daftar Artikel
                </button>
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between">
                    <PageTitle 
                        title="Preview Artikel" 
                        description="Melihat pratinjau konten artikel seperti yang akan terlihat oleh pengunjung."
                    />
                    <div class="mt-4 sm:mt-0">
                        <button
                            @click="router.visit(route('admin.articles.edit', { article: article.id }))"
                            class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                        >
                            <EditIcon class="-ml-1 mr-2 h-4 w-4" />
                            Edit Artikel
                        </button>
                    </div>
                </div>
            </div>
        </template>

        <div class="mt-6 rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden dark:border-gray-700 dark:bg-gray-800">
            <!-- Article Header -->
            <div class="p-6 md:p-10 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center space-x-2 mb-4">
                    <span 
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                        :class="[
                            article.status === 'published' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' :
                            article.status === 'draft' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' :
                            'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
                        ]"
                    >
                        {{ article.status.charAt(0).toUpperCase() + article.status.slice(1) }}
                    </span>
                    <span v-if="article.is_featured" class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        Pilihan Utama
                    </span>
                </div>
                
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white md:text-4xl mb-4">
                    {{ article.title }}
                </h1>
                
                <div class="flex flex-wrap items-center gap-y-2 text-sm text-gray-500 dark:text-gray-400 space-x-4">
                    <div class="flex items-center">
                        <UserIcon class="mr-1.5 h-4 w-4 flex-shrink-0" />
                        {{ article.author?.name || 'Unknown Author' }}
                    </div>
                    <div class="flex items-center">
                        <TagIcon class="mr-1.5 h-4 w-4 flex-shrink-0" />
                        {{ article.category?.name || 'Uncategorized' }}
                    </div>
                    <div class="flex items-center" v-if="article.published_at">
                        <CalendarIcon class="mr-1.5 h-4 w-4 flex-shrink-0" />
                        {{ new Date(article.published_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) }}
                    </div>
                    <div class="flex items-center">
                        <EyeIcon class="mr-1.5 h-4 w-4 flex-shrink-0" />
                        {{ article.views || 0 }} Views
                    </div>
                </div>
            </div>

            <!-- Featured Image -->
            <div v-if="article.featured_image_path" class="w-full h-64 md:h-96 relative">
                <img 
                    :src="`/storage/${article.featured_image_path}`" 
                    :alt="article.title"
                    class="w-full h-full object-cover"
                />
            </div>

            <!-- Article Body -->
            <div class="p-6 md:p-10">
                <div v-if="article.excerpt" class="mb-8 border-l-4 border-blue-500 pl-4 italic text-gray-600 dark:border-blue-400 dark:text-gray-400 text-lg">
                    {{ article.excerpt }}
                </div>
                
                <div class="prose prose-blue max-w-none dark:prose-invert" v-html="article.content"></div>
            </div>
        </div>
    </DashboardContainer>
</template>
