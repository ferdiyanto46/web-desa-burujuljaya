<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { PlusIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import StatusFilter from '@/components/filters/StatusFilter.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { route } from '@/lib/route';
import type { PaginationData, Article } from '@/types';

interface Props {
    articles: PaginationData<Article>;
    filters?: {
        search?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const debouncedSearch = useDebounceFn(() => {
    router.get(
        route('admin.articles.index'),
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch(status, () => {
    debouncedSearch();
});

const columns = [
    { key: 'title', label: 'Judul Artikel', sortable: true },
    { key: 'status', label: 'Status', sortable: true, align: 'center' as const },
    { key: 'views', label: 'Views', sortable: true, align: 'right' as const },
    { key: 'published_at', label: 'Tanggal Publikasi', sortable: true, align: 'right' as const },
];

const destroy = (item: Article | any) => {
    const key = item.ulid || item.id;
    if (confirm('Apakah Anda yakin ingin menghapus artikel ini?')) {
        router.delete(route('admin.articles.destroy', { article: key }), {
            preserveScroll: true,
        });
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'published': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'draft': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'archived': return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};
</script>

<template>
    <Head title="Manajemen Artikel" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Artikel" 
                description="Kelola publikasi dan berita desa."
            >
                <button
                    type="button"
                    @click="router.visit(route('admin.articles.create'))"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Artikel
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari artikel..."
                @search="debouncedSearch"
            >
                <template #filters>
                    <StatusFilter v-model="status" />
                </template>
            </DataTableToolbar>

            <DataTable
                :columns="columns"
                :items="articles.data"
            >
                <template #cell-title="{ item }">
                    <div class="flex items-center">
                        <div v-if="item.featured_image_path" class="h-10 w-10 flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-md object-cover" :src="`/storage/${item.featured_image_path}`" alt="" />
                        </div>
                        <div v-else class="h-10 w-10 flex-shrink-0 mr-3 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center text-gray-400">
                            No
                        </div>
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900 dark:text-white">{{ item.title }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ item.category?.name || 'Uncategorized' }}</span>
                        </div>
                    </div>
                </template>

                <template #cell-status="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', getStatusColor(item.status)]">
                        {{ item.status }}
                    </span>
                </template>
                
                <template #cell-views="{ item }">
                    {{ item.views || 0 }}
                </template>
                
                <template #cell-published_at="{ item }">
                    {{ item.published_at ? new Date(item.published_at).toLocaleDateString('id-ID') : '-' }}
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <button
                            @click="router.visit(route('admin.articles.edit', { article: item.ulid || item.id }))"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 rounded hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors"
                            title="Edit Artikel"
                        >
                            <EditIcon class="h-4 w-4" />
                        </button>
                        <button
                            @click="destroy(item)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1.5 rounded hover:bg-red-50 dark:hover:bg-red-900/30 transition-colors"
                            title="Hapus Artikel"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="articles" />
        </div>
    </DashboardContainer>
</template>
