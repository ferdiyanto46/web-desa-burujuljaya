<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { 
    AlertTriangle, 
    Edit, 
    Layers, 
    Loader2, 
    Plus, 
    Search, 
    Trash2, 
    Users 
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import ManagePositionsModal from '@/components/admin/ManagePositionsModal.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface ApparatusPosition {
    id: number;
    name: string;
    order: number;
}

interface Apparatus {
    id: number;
    ulid: string;
    position_id: number;
    name: string;
    nip: string | null;
    education: string | null;
    photo_path: string | null;
    photo_url: string | null;
    is_active: boolean;
    created_at: string;
    position?: ApparatusPosition;
}

interface PaginatedData<T> {
    data: T[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{
        url: string | null;
        label: string;
        active: boolean;
    }>;
}

const props = defineProps<{
    apparatuses: PaginatedData<Apparatus>;
    positions: ApparatusPosition[];
    filters: {
        search?: string;
        position_id?: string | number;
        is_active?: boolean;
    };
}>();

const search = ref(props.filters.search || '');
const positionFilter = ref(props.filters.position_id || '');
const deleteModalOpen = ref(false);
const positionsModalOpen = ref(false);
const itemToDelete = ref<Apparatus | null>(null);
const isDeleting = ref(false);

const debouncedFilter = useDebounceFn(() => {
    router.get(
        route('admin.apparatus.index'),
        {
            search: search.value || undefined,
            position_id: positionFilter.value || undefined,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        }
    );
}, 300);

watch([search, positionFilter], () => {
    debouncedFilter();
});

const confirmDelete = (item: Apparatus) => {
    itemToDelete.value = item;
    deleteModalOpen.value = true;
};

const executeDelete = () => {
    if (!itemToDelete.value) return;

    isDeleting.value = true;
    router.delete(route('admin.apparatus.destroy', itemToDelete.value.ulid), {
        preserveScroll: true,
        onSuccess: () => {
            deleteModalOpen.value = false;
            itemToDelete.value = null;
        },
        onFinish: () => {
            isDeleting.value = false;
        },
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Perangkat Desa" />

        <div class="space-y-6 max-w-7xl mx-auto pb-12">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <PageTitle 
                    title="Perangkat Desa" 
                    subtitle="Kelola struktur organisasi, pamong, dan staf pemerintahan desa" 
                />
                
                <div class="flex items-center gap-2">
                    <Button 
                        type="button" 
                        variant="outline"
                        class="border-emerald-600/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 flex items-center gap-2"
                        @click="positionsModalOpen = true"
                    >
                        <Layers class="w-4 h-4" />
                        <span>Kelola Jabatan ({{ positions.length }})</span>
                    </Button>

                    <Link :href="route('admin.apparatus.create')">
                        <Button 
                            type="button" 
                            variant="default"
                            class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-2"
                        >
                            <Plus class="w-4 h-4" />
                            <span>Tambah Perangkat</span>
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Filter & Search Toolbar -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 p-4 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-stretch sm:items-center">
                    <div class="flex-1 flex flex-col sm:flex-row gap-3">
                        <!-- Search Box -->
                        <div class="relative flex-1 max-w-md">
                            <Search class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" />
                            <Input 
                                v-model="search" 
                                placeholder="Cari nama, NIP, atau pendidikan..." 
                                class="pl-9 h-10 w-full"
                            />
                        </div>

                        <!-- Position Filter -->
                        <div class="w-full sm:w-56">
                            <select 
                                v-model="positionFilter"
                                class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                            >
                                <option value="">Semua Jabatan</option>
                                <option 
                                    v-for="pos in positions" 
                                    :key="pos.id" 
                                    :value="pos.id"
                                >
                                    {{ pos.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="text-xs text-gray-500 flex items-center gap-1.5 self-end sm:self-center">
                        <span>Total: <strong>{{ apparatuses.total }}</strong> perangkat</span>
                    </div>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-700 dark:text-gray-300">
                        <thead class="bg-gray-50/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-800 text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            <tr>
                                <th scope="col" class="py-3.5 px-4 w-12 text-center">No</th>
                                <th scope="col" class="py-3.5 px-4">Aparatur Desa</th>
                                <th scope="col" class="py-3.5 px-4">Jabatan</th>
                                <th scope="col" class="py-3.5 px-4">NIP</th>
                                <th scope="col" class="py-3.5 px-4">Pendidikan</th>
                                <th scope="col" class="py-3.5 px-4 text-center">Status</th>
                                <th scope="col" class="py-3.5 px-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60">
                            <tr 
                                v-for="(item, index) in apparatuses.data" 
                                :key="item.id"
                                class="hover:bg-gray-50/60 dark:hover:bg-gray-800/40 transition-colors"
                            >
                                <!-- Index -->
                                <td class="py-4 px-4 text-center text-xs text-gray-400">
                                    {{ (apparatuses.current_page - 1) * apparatuses.per_page + index + 1 }}
                                </td>

                                <!-- Apparatus Profile -->
                                <td class="py-4 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-11 h-11 rounded-full bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 overflow-hidden flex items-center justify-center flex-shrink-0 shadow-sm">
                                            <img 
                                                v-if="item.photo_url" 
                                                :src="item.photo_url" 
                                                :alt="item.name" 
                                                class="w-full h-full object-cover"
                                            />
                                            <span v-else class="text-emerald-700 dark:text-emerald-300 font-bold text-sm">
                                                {{ item.name.charAt(0).toUpperCase() }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-gray-100 leading-tight">
                                                {{ item.name }}
                                            </p>
                                            <span class="text-xs text-gray-400">
                                                {{ item.position?.name || 'Perangkat Desa' }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Position Badge -->
                                <td class="py-4 px-4">
                                    <Badge variant="outline" class="bg-emerald-50 text-emerald-700 dark:bg-emerald-950/30 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800/60">
                                        {{ item.position?.name || '-' }}
                                    </Badge>
                                </td>

                                <!-- NIP -->
                                <td class="py-4 px-4 text-xs font-mono text-gray-600 dark:text-gray-400">
                                    {{ item.nip || '-' }}
                                </td>

                                <!-- Education -->
                                <td class="py-4 px-4 text-xs text-gray-600 dark:text-gray-400">
                                    {{ item.education || '-' }}
                                </td>

                                <!-- Status Active -->
                                <td class="py-4 px-4 text-center">
                                    <span 
                                        v-if="item.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-950/50 dark:text-green-400"
                                    >
                                        Aktif
                                    </span>
                                    <span 
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400"
                                    >
                                        Nonaktif
                                    </span>
                                </td>

                                <!-- Action Buttons -->
                                <td class="py-4 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <Link :href="route('admin.apparatus.edit', item.ulid)">
                                            <Button 
                                                type="button" 
                                                variant="ghost" 
                                                size="sm"
                                                class="h-8 w-8 p-0 text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/30"
                                                title="Edit Data"
                                            >
                                                <Edit class="w-4 h-4" />
                                            </Button>
                                        </Link>

                                        <Button 
                                            type="button" 
                                            variant="ghost" 
                                            size="sm"
                                            class="h-8 w-8 p-0 text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/30"
                                            title="Hapus Data"
                                            @click="confirmDelete(item)"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Empty State -->
                            <tr v-if="apparatuses.data.length === 0">
                                <td colspan="7" class="py-12 px-4 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-400 space-y-2">
                                        <Users class="w-10 h-10 stroke-1 text-gray-300 dark:text-gray-600" />
                                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Tidak ada data perangkat desa yang ditemukan</p>
                                        <p class="text-xs text-gray-400">Klik tombol "Tambah Perangkat" untuk menambahkan data baru.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                <div v-if="apparatuses.links && apparatuses.links.length > 3" class="px-6 py-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                    <p class="text-xs text-gray-500">
                        Menampilkan {{ apparatuses.from || 0 }} - {{ apparatuses.to || 0 }} dari {{ apparatuses.total }} data
                    </p>
                    <div class="flex items-center gap-1">
                        <template v-for="(link, i) in apparatuses.links" :key="i">
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                class="px-3 py-1 text-xs rounded-lg border transition-colors"
                                :class="[
                                    link.active 
                                        ? 'bg-emerald-600 border-emerald-600 text-white font-semibold' 
                                        : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'
                                ]"
                                v-html="link.label"
                            />
                            <span 
                                v-else
                                class="px-3 py-1 text-xs rounded-lg border border-gray-100 dark:border-gray-800 text-gray-300 dark:text-gray-600 cursor-not-allowed"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div 
            v-if="deleteModalOpen" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
        >
            <div class="bg-white dark:bg-gray-900 rounded-2xl max-w-md w-full p-6 shadow-2xl border border-gray-200 dark:border-gray-800 space-y-4">
                <div class="flex items-center gap-3 text-red-600">
                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-950/50 flex items-center justify-center">
                        <AlertTriangle class="w-5 h-5" />
                    </div>
                    <div>
                        <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Hapus Perangkat Desa</h3>
                        <p class="text-xs text-gray-500">Konfirmasi tindakan penghapusan</p>
                    </div>
                </div>

                <p class="text-sm text-gray-600 dark:text-gray-300">
                    Apakah Anda yakin ingin menghapus data perangkat <strong>"{{ itemToDelete?.name }}"</strong>? Data yang dihapus dapat dipulihkan melalui log/arsip.
                </p>

                <div class="flex justify-end gap-2 pt-2">
                    <Button 
                        type="button" 
                        variant="outline"
                        :disabled="isDeleting"
                        @click="deleteModalOpen = false"
                    >
                        Batal
                    </Button>
                    <Button 
                        type="button" 
                        variant="destructive"
                        class="bg-red-600 hover:bg-red-700 text-white flex items-center gap-2"
                        :disabled="isDeleting"
                        @click="executeDelete"
                    >
                        <Loader2 v-if="isDeleting" class="w-4 h-4 animate-spin" />
                        <Trash2 v-else class="w-4 h-4" />
                        <span>{{ isDeleting ? 'Menghapus...' : 'Ya, Hapus' }}</span>
                    </Button>
                </div>
            </div>
        </div>

        <!-- Manage Positions Modal -->
        <ManagePositionsModal 
            v-model:open="positionsModalOpen" 
            :positions="positions" 
        />
    </AdminLayout>
</template>
