<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { PlusIcon, EditIcon, TrashIcon, PinIcon, FileIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import StatusFilter from '@/components/filters/StatusFilter.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { route } from '@/lib/route';
import { DocumentService } from '@/services/document';
import type { PaginationData } from '@/types';
import type { Document } from '@/types/document';

interface Props {
    documents: PaginationData<Document>;
    filters?: {
        search?: string;
        category_id?: string;
        status?: string;
        is_pinned?: boolean;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const debouncedSearch = useDebounceFn(() => {
    DocumentService.adminIndex(
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch(status, () => {
    debouncedSearch();
});

const columns = [
    { key: 'title', label: 'Judul Dokumen', sortable: true },
    { key: 'category', label: 'Kategori', sortable: false },
    { key: 'status', label: 'Status', sortable: true, align: 'center' as const },
    { key: 'published_at', label: 'Tanggal Publikasi', sortable: true, align: 'right' as const },
];

const destroy = (id: string | number) => {
    if (confirm('Apakah Anda yakin ingin menghapus dokumen ini?')) {
        DocumentService.destroy(id.toString());
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
    <Head title="Manajemen Dokumen" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Dokumen" 
                description="Kelola dokumen desa."
            >
                <button
                    type="button"
                    @click="DocumentService.create()"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Dokumen
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari dokumen..."
                @search="debouncedSearch"
            >
                <template #filters>
                    <StatusFilter v-model="status" />
                </template>
            </DataTableToolbar>

            <DataTable
                :columns="columns"
                :items="documents.data"
            >
                <template #cell-title="{ item }">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0 mr-3 bg-blue-100 dark:bg-blue-900/30 rounded-md flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <FileIcon class="h-5 w-5" />
                        </div>
                        <div class="flex flex-col">
                            <span class="font-medium flex items-center gap-1 text-gray-900 dark:text-white">
                                {{ item.title }}
                                <PinIcon v-if="item.is_pinned" class="w-3 h-3 text-red-500" />
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">{{ item.file_name }} ({{ item.extension }})</span>
                        </div>
                    </div>
                </template>

                <template #cell-category="{ item }">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ item.category?.name || '-' }}
                    </span>
                </template>

                <template #cell-status="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', getStatusColor(item.status)]">
                        {{ item.status }}
                    </span>
                </template>
                
                <template #cell-published_at="{ item }">
                    {{ item.published_at ? new Date(item.published_at).toLocaleDateString('id-ID') : '-' }}
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <button
                            v-if="item.permissions?.update"
                            @click="router.visit(item.links?.edit || route('admin.documents.edit', item.ulid))"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1"
                            title="Edit"
                        >
                            <EditIcon class="h-4 w-4" />
                        </button>
                        <button
                            v-if="item.permissions?.delete"
                            @click="destroy(item.ulid)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1"
                            title="Hapus"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="documents" />
        </div>
    </DashboardContainer>
</template>
