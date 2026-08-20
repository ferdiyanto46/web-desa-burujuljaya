<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import DocumentForm from '@/components/Admin/Documents/DocumentForm.vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { DocumentCategory } from '@/types/document';

interface Props {
    categories: DocumentCategory[];
}

defineProps<Props>();
</script>

<template>
    <Head title="Tambah Dokumen" />

    <DashboardContainer>
        <template #header>
            <div class="flex items-center gap-4">
                <button
                    @click="router.visit(route('admin.documents.index'))"
                    class="p-2 -ml-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 text-gray-500 dark:text-gray-400 transition-colors"
                >
                    <ArrowLeftIcon class="w-5 h-5" />
                </button>
                <PageTitle 
                    title="Tambah Dokumen" 
                    description="Tambahkan dokumen baru."
                />
            </div>
        </template>

        <div class="mt-8 max-w-4xl bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <DocumentForm :categories="categories" />
        </div>
    </DashboardContainer>
</template>
