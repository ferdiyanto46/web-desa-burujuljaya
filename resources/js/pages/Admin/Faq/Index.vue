<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { Plus, Edit, Trash2, Eye, RefreshCw, AlertTriangle } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import DataTable from '@/components/tables/DataTable.vue';
import DataTablePagination from '@/components/tables/DataTablePagination.vue';
import DataTableToolbar from '@/components/tables/DataTableToolbar.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { PaginationData } from '@/types';
import type { Faq } from '@/types/faq';

const props = defineProps<{
    faqs: PaginationData<Faq>;
    filters: {
        search?: string;
        status?: string;
        is_featured?: string;
    };
}>();

const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status || '');
const featuredFilter = ref(props.filters.is_featured || '');

const columns = [
    { key: 'question', label: 'Pertanyaan' },
    { key: 'category', label: 'Kategori' },
    { key: 'sort_order', label: 'Urutan' },
    { key: 'is_featured', label: 'Sorotan' },
    { key: 'status', label: 'Status' },
    { key: 'view_count', label: 'Dilihat' },
];

const updateFilters = useDebounceFn(() => {
    router.get(
        route('admin.faqs.index'),
        { 
            search: search.value,
            status: statusFilter.value,
            is_featured: featuredFilter.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300);

watch(search, updateFilters);
watch(statusFilter, updateFilters);
watch(featuredFilter, updateFilters);

const getStatusVariant = (status: string) => {
    switch (status) {
        case 'published': return 'default';
        case 'draft': return 'secondary';
        case 'archived': return 'outline';
        default: return 'outline';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'published': return 'Diterbitkan';
        case 'draft': return 'Draf';
        case 'archived': return 'Diarsipkan';
        default: return status;
    }
};

const destroy = (faq: Faq) => {
    if (confirm('Apakah Anda yakin ingin menghapus FAQ ini?')) {
        router.delete(route('admin.faqs.destroy', faq.ulid));
    }
};

const forceDelete = (faq: Faq) => {
    if (confirm('PERINGATAN: Apakah Anda yakin ingin menghapus PERMANEN FAQ ini? Data tidak dapat dipulihkan.')) {
        router.delete(route('admin.faqs.force-delete', faq.ulid));
    }
};

const restore = (faq: Faq) => {
    if (confirm('Apakah Anda yakin ingin memulihkan FAQ ini?')) {
        router.post(route('admin.faqs.restore', faq.ulid));
    }
};
</script>

<template>
    <Head title="Kelola FAQ" />

    <AdminLayout>
        <div class="space-y-6">
            <PageTitle title="Kelola FAQ" description="Manajemen Pertanyaan yang Sering Diajukan">
                <template #actions>
                    <Button
                        as="a"
                        :href="route('admin.faqs.create')"
                    >
                        <Plus class="w-4 h-4 mr-2" />
                        Tambah FAQ
                    </Button>
                </template>
            </PageTitle>

            <div class="space-y-4">
                <DataTableToolbar>
                    <template #search>
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Cari FAQ..."
                            class="w-full sm:max-w-xs flex h-9 rounded-md border border-input bg-transparent px-3 py-1 text-sm shadow-sm transition-colors file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        />
                    </template>
                    <template #filters>
                        <select
                            v-model="statusFilter"
                            class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Semua Status</option>
                            <option value="published">Diterbitkan</option>
                            <option value="draft">Draf</option>
                            <option value="archived">Diarsipkan</option>
                        </select>
                        <select
                            v-model="featuredFilter"
                            class="flex h-9 items-center justify-between whitespace-nowrap rounded-md border border-input bg-transparent px-3 py-2 text-sm shadow-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-1 focus:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Semua Sorotan</option>
                            <option value="1">Disorot</option>
                            <option value="0">Tidak Disorot</option>
                        </select>
                    </template>
                </DataTableToolbar>

                <DataTable
                    :columns="columns"
                    :items="faqs.data"
                >
                    <template #cell-question="{ item }">
                        <div class="font-medium text-foreground">{{ item.question }}</div>
                        <div v-if="item.deleted_at" class="text-xs text-destructive mt-1 flex items-center">
                            <AlertTriangle class="w-3 h-3 mr-1" />
                            Dihapus pada {{ new Date(item.deleted_at).toLocaleDateString('id-ID') }}
                        </div>
                    </template>
                    
                    <template #cell-category="{ item }">
                        <span v-if="item.category" class="text-sm text-muted-foreground">{{ item.category }}</span>
                        <span v-else class="text-sm text-muted-foreground italic">Tidak ada</span>
                    </template>
                    
                    <template #cell-sort_order="{ item }">
                        <span class="text-sm">{{ item.sort_order }}</span>
                    </template>
                    
                    <template #cell-is_featured="{ item }">
                        <Badge v-if="item.is_featured" variant="secondary">Ya</Badge>
                        <span v-else class="text-muted-foreground">-</span>
                    </template>

                    <template #cell-status="{ item }">
                        <Badge :variant="getStatusVariant(item.status)">
                            {{ getStatusLabel(item.status) }}
                        </Badge>
                    </template>
                    
                    <template #cell-view_count="{ item }">
                        <span class="text-sm">{{ item.view_count.toLocaleString('id-ID') }}</span>
                    </template>

                    <template #actions="{ item }">
                        <div class="flex items-center justify-end gap-2">
                            <template v-if="!item.deleted_at">
                                <Button
                                    as="a"
                                    :href="route('admin.faqs.show', item.ulid)"
                                    variant="ghost"
                                    size="icon"
                                    title="Lihat"
                                >
                                    <Eye class="w-4 h-4" />
                                </Button>

                                <Button
                                    as="a"
                                    :href="route('admin.faqs.edit', item.ulid)"
                                    variant="ghost"
                                    size="icon"
                                    title="Edit"
                                >
                                    <Edit class="w-4 h-4" />
                                </Button>

                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                    @click="destroy(item)"
                                    title="Hapus"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </Button>
                            </template>
                            
                            <template v-else>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="text-green-600 hover:text-green-700 hover:bg-green-50"
                                    @click="restore(item)"
                                    title="Pulihkan"
                                >
                                    <RefreshCw class="w-4 h-4" />
                                </Button>
                                
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="text-destructive hover:text-destructive hover:bg-destructive/10"
                                    @click="forceDelete(item)"
                                    title="Hapus Permanen"
                                >
                                    <Trash2 class="w-4 h-4" />
                                </Button>
                            </template>
                        </div>
                    </template>
                </DataTable>

            </div>
        </div>
    </AdminLayout>
</template>
