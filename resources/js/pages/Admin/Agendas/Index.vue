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
import AdminLayout from '@/layouts/AdminLayout.vue';
import { route } from '@/lib/route';
import type { PaginationData, Agenda } from '@/types';

defineOptions({ layout: AdminLayout });

interface Props {
    agendas: PaginationData<Agenda>;
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
        route('admin.agendas.index'),
        { search: search.value, status: status.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch(status, () => {
    debouncedSearch();
});

const columns = [
    { key: 'title', label: 'Nama Kegiatan', sortable: true },
    { key: 'start_datetime', label: 'Waktu Mulai', sortable: true },
    { key: 'location', label: 'Lokasi', sortable: true },
    { key: 'event_status', label: 'Status Kegiatan', sortable: true, align: 'center' as const },
    { key: 'status', label: 'Publikasi', sortable: true, align: 'center' as const },
];

const destroy = (ulid: string) => {
    if (confirm('Apakah Anda yakin ingin membatalkan/menghapus agenda ini?')) {
        router.delete(route('admin.agendas.destroy', { agenda: ulid }));
    }
};

const getPublicationColor = (status: string) => {
    switch (status) {
        case 'published': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'draft': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'archived': return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};

const getEventStatusColor = (status: string) => {
    switch (status) {
        case 'upcoming': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'ongoing': return 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400';
        case 'finished': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'cancelled': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};
</script>

<template>
    <Head title="Manajemen Agenda" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Agenda Desa" 
                description="Kelola jadwal kegiatan dan acara desa."
            >
                <button
                    type="button"
                    @click="router.visit(route('admin.agendas.create'))"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Agenda
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari agenda..."
                @search="debouncedSearch"
            >
                <template #filters>
                    <StatusFilter v-model="status" />
                </template>
            </DataTableToolbar>

            <DataTable
                :columns="columns"
                :items="agendas.data"
            >
                <template #cell-title="{ item }">
                    <div class="flex items-center">
                        <div v-if="item.featured_image_path" class="h-10 w-10 flex-shrink-0 mr-3">
                            <img class="h-10 w-10 rounded-md object-cover" :src="`/storage/${item.featured_image_path}`" alt="" />
                        </div>
                        <div v-else class="h-10 w-10 flex-shrink-0 mr-3 bg-gray-100 dark:bg-gray-800 rounded-md flex items-center justify-center text-gray-400">
                            No
                        </div>
                        <span class="font-medium text-gray-900 dark:text-white">{{ item.title }}</span>
                    </div>
                </template>

                <template #cell-start_datetime="{ item }">
                    <div class="flex flex-col">
                        <span class="text-gray-900 dark:text-gray-200">{{ item.start_datetime.substring(0, 10) }}</span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ item.start_datetime.substring(11, 16) }} WIB</span>
                    </div>
                </template>
                
                <template #cell-location="{ item }">
                    <span class="text-gray-900 dark:text-gray-200 truncate max-w-[150px] inline-block" :title="item.location">
                        {{ item.location }}
                    </span>
                </template>

                <template #cell-event_status="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium uppercase', getEventStatusColor(item.event_status)]">
                        {{ item.event_status }}
                    </span>
                </template>

                <template #cell-status="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium uppercase', getPublicationColor(item.status)]">
                        {{ item.status }}
                    </span>
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <button
                            @click="router.visit(route('admin.agendas.edit', { agenda: item.ulid }))"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1"
                            title="Edit"
                        >
                            <EditIcon class="h-4 w-4" />
                        </button>
                        <button
                            @click="destroy(item.ulid)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1"
                            title="Hapus"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="agendas" />
        </div>
    </DashboardContainer>
</template>
