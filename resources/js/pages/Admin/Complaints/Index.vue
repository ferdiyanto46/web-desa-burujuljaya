<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { EyeIcon, TrashIcon } from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import CategoryFilter from '@/components/filters/CategoryFilter.vue';
import StatusFilter from '@/components/filters/StatusFilter.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { route } from '@/lib/route';
import { ComplaintService } from '@/services/complaint';
import type { PaginationData, Category } from '@/types';

interface Complaint {
    id: number;
    ulid: string;
    title: string;
    tracking_code?: string;
    status: string;
    created_at: string;
    category?: { name: string };
    reporter_name?: string;
    is_anonymous?: boolean;
}

interface Props {
    complaints: PaginationData<Complaint> | { data: Complaint[]; meta?: any; links?: any };
    categories: Category[] | { data: Category[] };
    filters?: {
        search?: string;
        category_id?: string;
        status?: string;
    };
}

const props = defineProps<Props>();

const categoryList = computed(() => {
    const raw = props.categories;
    return Array.isArray(raw) ? raw : ((raw as any)?.data ?? []);
});

const complaintList = computed(() => {
    if (!props.complaints) return [];
    if (Array.isArray(props.complaints)) return props.complaints;
    if (Array.isArray((props.complaints as any).data)) return (props.complaints as any).data;
    return [];
});

const complaintStatusOptions = [
    { label: 'Semua Status', value: '' },
    { label: 'Belum Dibaca', value: 'unread' },
    { label: 'Sedang Diproses', value: 'processing' },
    { label: 'Selesai', value: 'resolved' },
    { label: 'Ditolak', value: 'rejected' },
];

const search = ref(props.filters?.search || '');
const category_id = ref(props.filters?.category_id || '');
const status = ref(props.filters?.status || '');

const debouncedSearch = useDebounceFn(() => {
    ComplaintService.adminIndex(
        { search: search.value, category_id: category_id.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch([category_id, status], () => {
    debouncedSearch();
});

const columns = [
    { key: 'tracking_code', label: 'Kode Tiket', sortable: false },
    { key: 'title', label: 'Judul Pengaduan', sortable: false },
    { key: 'category', label: 'Kategori', sortable: false },
    { key: 'status', label: 'Status', sortable: false, align: 'center' as const },
    { key: 'created_at', label: 'Tanggal', sortable: false, align: 'right' as const },
];

const destroy = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengaduan ini?')) {
        router.delete(route('admin.complaints.destroy', { complaint: id }));
    }
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'unread': return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'resolved': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'unread': return 'Belum Dibaca';
        case 'processing': return 'Diproses';
        case 'resolved': return 'Selesai';
        case 'rejected': return 'Ditolak';
        default: return status;
    }
};
</script>

<template>
    <Head title="Manajemen Pengaduan" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Pengaduan Masyarakat" 
                description="Kelola dan tindak lanjuti laporan dari warga desa."
            />
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari pengaduan..."
                @search="debouncedSearch"
            >
                <template #filters>
                    <CategoryFilter v-model="category_id" :categories="categoryList" />
                    <StatusFilter v-model="status" :options="complaintStatusOptions" />
                </template>
            </DataTableToolbar>

            <DataTable
                :columns="columns"
                :items="complaintList"
            >
                <template #cell-tracking_code="{ item }">
                    <span class="font-mono text-xs font-semibold px-2 py-1 bg-gray-100 dark:bg-gray-800 rounded">{{ item.tracking_code || item.ulid }}</span>
                </template>

                <template #cell-title="{ item }">
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.title }}</span>
                </template>
                
                <template #cell-category="{ item }">
                    {{ item.category?.name || '-' }}
                </template>

                <template #cell-status="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', getStatusColor(item.status)]">
                        {{ getStatusLabel(item.status) }}
                    </span>
                </template>
                
                <template #cell-created_at="{ item }">
                    {{ item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) : '-' }}
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <Link
                            :href="route('admin.complaints.show', item.id)"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1.5 hover:bg-blue-50 dark:hover:bg-blue-950/40 rounded transition-colors"
                            title="Detail Pengaduan"
                        >
                            <EyeIcon class="h-4 w-4" />
                        </Link>
                        <button
                            @click="destroy(item.id)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1.5 hover:bg-red-50 dark:hover:bg-red-950/40 rounded transition-colors"
                            title="Hapus"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="(complaints as any)" />
        </div>
    </DashboardContainer>
</template>
