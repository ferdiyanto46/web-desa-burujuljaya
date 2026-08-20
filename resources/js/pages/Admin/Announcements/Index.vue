<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { PlusIcon, EditIcon, TrashIcon, PinIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import StatusFilter from '@/components/filters/StatusFilter.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { route } from '@/lib/route';
import { deleteAnnouncement } from '@/services/announcement';
import type { PaginationData } from '@/types';
import type { Announcement } from '@/types/announcement';

interface Props {
    announcements: PaginationData<Announcement>;
    filters?: {
        search?: string;
        status?: string;
        is_pinned?: boolean;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

const debouncedSearch = useDebounceFn(() => {
    router.get(
        route('admin.announcements.index'),
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch(status, () => {
    debouncedSearch();
});

const columns = [
    { key: 'title', label: 'Judul Pengumuman', sortable: true },
    { key: 'status', label: 'Status', sortable: true, align: 'center' as const },
    { key: 'published_at', label: 'Tanggal Publikasi', sortable: true, align: 'right' as const },
];

const destroy = (id: string | number) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengumuman ini?')) {
        deleteAnnouncement(id.toString());
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
    <Head title="Manajemen Pengumuman" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Pengumuman" 
                description="Kelola pengumuman desa."
            >
                <button
                    type="button"
                    @click="router.visit(route('admin.announcements.create'))"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Pengumuman
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari pengumuman..."
                @search="debouncedSearch"
            >
                <template #filters>
                    <StatusFilter v-model="status" />
                </template>
            </DataTableToolbar>

            <DataTable
                :columns="columns"
                :items="announcements.data"
            >
                <template #cell-title="{ item }">
                    <div class="flex items-center">
                        <div v-if="item.featured_image_url" class="h-10 w-10 flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-md object-cover" :src="item.featured_image_url" alt="" />
                        </div>
                        <div v-else class="h-10 w-10 flex-shrink-0 mr-3 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center text-gray-400">
                            No
                        </div>
                        <div class="flex flex-col">
                            <span class="font-medium flex items-center gap-1 text-gray-900 dark:text-white">
                                {{ item.title }}
                                <PinIcon v-if="item.is_pinned" class="w-3 h-3 text-red-500" />
                            </span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-xs">{{ item.excerpt || 'Tidak ada excerpt' }}</span>
                        </div>
                    </div>
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
                            v-if="item.permissions.update"
                            @click="router.visit(item.links.edit)"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1"
                            title="Edit"
                        >
                            <EditIcon class="h-4 w-4" />
                        </button>
                        <button
                            v-if="item.permissions.delete"
                            @click="destroy(item.id)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1"
                            title="Hapus"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="announcements" />
        </div>
    </DashboardContainer>
</template>
