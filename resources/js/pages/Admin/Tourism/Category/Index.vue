<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { PlusIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { route } from '@/lib/route';
import type { PaginationData, TourismCategory } from '@/types';

interface Props {
    categories: PaginationData<TourismCategory>;
    filters?: {
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters?.search || '');

const debouncedSearch = useDebounceFn(() => {
    router.get(
        route('admin.tourism.category.index'),
        { search: search.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
}, 300);

watch(search, () => {
    debouncedSearch();
});

const columns = [
    { key: 'name', label: 'Nama Kategori', sortable: true },
    { key: 'slug', label: 'Slug', sortable: true },
    { key: 'description', label: 'Deskripsi', sortable: true },
];

const destroy = (id: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori wisata ini?')) {
        router.delete(route('admin.tourism-categories.destroy', { tourism_category: id }));
    }
};
</script>

<template>
    <Head title="Manajemen Kategori Wisata" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Kategori Wisata" 
                description="Kelola kategori pariwisata desa."
            >
                <button
                    type="button"
                    @click="router.visit(route('admin.tourism-categories.create'))"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Kategori
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTableToolbar
                v-model:search="search"
                placeholder="Cari kategori..."
                @search="debouncedSearch"
            />

            <DataTable
                :columns="columns"
                :items="categories.data"
            >
                <template #cell-name="{ item }">
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <button
                            @click="router.visit(route('admin.tourism-categories.edit', { tourism_category: item.ulid }))"
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
            
            <DataTablePagination :pagination="categories" />
        </div>
    </DashboardContainer>
</template>
