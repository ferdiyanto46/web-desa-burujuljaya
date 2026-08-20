<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { PlusIcon, EditIcon, TrashIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import { route } from '@/lib/route';
import type { PaginationData } from '@/types';

interface User {
    id: number;
    name: string;
    email: string;
    is_active: boolean;
    created_at: string;
    roles?: Array<{name: string}>;
}

interface Props {
    users: PaginationData<User>;
}

defineProps<Props>();

const columns = [
    { key: 'name', label: 'Nama Lengkap', sortable: false },
    { key: 'email', label: 'Email', sortable: false },
    { key: 'role', label: 'Role', sortable: false, align: 'center' as const },
    { key: 'is_active', label: 'Status', sortable: false, align: 'center' as const },
    { key: 'created_at', label: 'Terdaftar', sortable: false, align: 'right' as const },
];

const destroy = (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus pengguna ini?')) {
        router.delete(route('admin.users.destroy', { user: id }));
    }
};
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Pengguna" 
                description="Kelola akun pengguna sistem."
            >
                <button
                    type="button"
                    @click="router.visit(route('admin.users.create'))"
                    class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:w-auto"
                >
                    <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                    Tambah Pengguna
                </button>
            </PageTitle>
        </template>

        <div class="mt-8 flex flex-col">
            <DataTable
                :columns="columns"
                :items="users.data"
            >
                <template #cell-name="{ item }">
                    <span class="font-medium text-gray-900 dark:text-white">{{ item.name }}</span>
                </template>

                <template #cell-email="{ item }">
                    {{ item.email }}
                </template>
                
                <template #cell-role="{ item }">
                    <span v-if="item.roles && item.roles.length > 0" class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                        {{ item.roles[0].name }}
                    </span>
                    <span v-else class="text-gray-500">-</span>
                </template>

                <template #cell-is_active="{ item }">
                    <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium', item.is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400']">
                        {{ item.is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </template>
                
                <template #cell-created_at="{ item }">
                    {{ item.created_at ? new Date(item.created_at).toLocaleDateString('id-ID') : '-' }}
                </template>

                <template #actions="{ item }">
                    <div class="flex items-center justify-end space-x-2">
                        <button
                            @click="router.visit(route('admin.users.edit', item.id))"
                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 p-1"
                            title="Edit"
                        >
                            <EditIcon class="h-4 w-4" />
                        </button>
                        <button
                            @click="destroy(item.id)"
                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 p-1"
                            title="Hapus"
                        >
                            <TrashIcon class="h-4 w-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
            
            <DataTablePagination :pagination="users" />
        </div>
    </DashboardContainer>
</template>
