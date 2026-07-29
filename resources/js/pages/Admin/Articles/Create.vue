<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import ArticleForm from '@/components/articles/ArticleForm.vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { ArticleCategory } from '@/types';

interface Props {
    categories: ArticleCategory[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Tambah Artikel" />

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
                    title="Tambah Artikel Baru" 
                    description="Buat publikasi, berita, atau pengumuman baru untuk warga desa."
                />
            </div>
        </template>

        <div class="mt-6">
            <ArticleForm 
                :categories="categories" 
                :submit-route="route('admin.articles.store')"
                :cancel-route="route('admin.articles.index')"
                :is-edit="false"
            />
        </div>
    </DashboardContainer>
</template>
