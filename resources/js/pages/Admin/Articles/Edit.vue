<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import { computed } from 'vue';
import ArticleForm from '@/components/articles/ArticleForm.vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { Article, ArticleCategory } from '@/types';

interface Props {
    article: Article | { data: Article };
    categories: ArticleCategory[];
}

const props = defineProps<Props>();

const resolvedArticle = computed<Article>(() => {
    return ((props.article as any)?.data || props.article) as Article;
});

const submitRoute = computed(() => {
    const art = resolvedArticle.value;
    const key = art?.ulid || art?.id;
    return route('admin.articles.update', { article: key });
});
</script>

<template>
    <Head title="Edit Artikel" />

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
                <PageTitle 
                    title="Edit Artikel" 
                    :description="`Melakukan perubahan pada artikel: ${resolvedArticle.title || ''}`"
                />
            </div>
        </template>

        <div class="mt-6">
            <ArticleForm 
                :article="resolvedArticle"
                :categories="categories" 
                :submit-route="submitRoute"
                :cancel-route="route('admin.articles.index')"
                :is-edit="true"
            />
        </div>
    </DashboardContainer>
</template>
