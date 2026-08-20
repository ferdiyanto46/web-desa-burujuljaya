<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { Plus as PlusIcon, Image as PhotoIcon, Pencil as PencilSquareIcon, Trash2 as TrashIcon } from 'lucide-vue-next'
import DataTable from '@/components/tables/DataTable.vue'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { PaginationData } from '@/types'
import type { Gallery } from '@/types/gallery'

defineOptions({ layout: AdminLayout })

defineProps<{
    galleries: PaginationData<Gallery>
    filters: {
        search?: string
        status?: string
        is_pinned?: string
    }
}>()

const columns = [
    { key: 'title', label: 'Judul' },
    { key: 'photos_count', label: 'Jml Foto' },
    { key: 'status', label: 'Status' },
    { key: 'published_at', label: 'Tgl Publikasi' },
    { key: 'actions', label: 'Aksi', sortable: false }
]

const filterOptions = [
    {
        key: 'status',
        label: 'Status',
        options: [
            { value: '', label: 'Semua Status' },
            { value: 'published', label: 'Published' },
            { value: 'draft', label: 'Draft' },
            { value: 'archived', label: 'Archived' }
        ]
    }
]

const deleteGallery = (gallery: Gallery) => {
    if (confirm(`Apakah Anda yakin ingin menghapus galeri "${gallery.title}"? Semua foto di dalamnya juga akan terhapus secara logis.`)) {
        router.delete(route('admin.galleries.destroy', gallery.ulid), {
            preserveScroll: true
        })
    }
}
</script>

<template>
    <Head title="Manajemen Galeri" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manajemen Galeri</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Kelola album foto dan dokumentasi kegiatan desa.</p>
            </div>
            <Link
                :href="route('admin.galleries.create')"
                class="inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition-colors sm:w-auto"
            >
                <PlusIcon class="-ml-1 mr-2 h-4 w-4" />
                Tambah Galeri
            </Link>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden">
            <DataTable
                :columns="columns"
                :items="galleries.data"
                :filters="filters"
                :filter-options="filterOptions"
                search-placeholder="Cari galeri..."
                route-name="admin.galleries.index"
            >
                <template #cell-title="{ item }">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 flex-shrink-0 overflow-hidden flex items-center justify-center">
                            <img v-if="item.featured_image_url" :src="item.featured_image_url" :alt="item.title" class="w-full h-full object-cover" />
                            <PhotoIcon v-else class="w-6 h-6 text-gray-400 dark:text-gray-500" />
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">{{ item.title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                                <span v-if="item.is_pinned" class="inline-block w-2 h-2 rounded-full bg-amber-500" title="Dipin"></span>
                                /{{ item.slug }}
                            </p>
                        </div>
                    </div>
                </template>

                <template #cell-photos_count="{ item }">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-semibold">
                        <PhotoIcon class="w-3.5 h-3.5" />
                        {{ item.photos_count || 0 }}
                    </span>
                </template>

                <template #cell-status="{ item }">
                    <span 
                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                        :class="[
                            item.status === 'published' 
                                ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-400' 
                                : item.status === 'draft' 
                                    ? 'bg-amber-100 text-amber-800 dark:bg-amber-950/60 dark:text-amber-400' 
                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-400'
                        ]"
                    >
                        {{ item.status }}
                    </span>
                </template>

                <template #cell-published_at="{ item }">
                    <span class="text-sm text-gray-600 dark:text-gray-400">
                        {{ item.published_at ? new Date(item.published_at).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' }) : '-' }}
                    </span>
                </template>

                <template #cell-actions="{ item }">
                    <div class="flex items-center justify-end gap-2">
                        <Link
                            :href="route('admin.galleries.edit', item.ulid)"
                            class="p-2 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 rounded-lg transition-colors"
                            title="Edit"
                        >
                            <PencilSquareIcon class="w-4 h-4" />
                        </Link>
                        <button
                            @click="deleteGallery(item)"
                            class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/30 rounded-lg transition-colors"
                            title="Hapus"
                        >
                            <TrashIcon class="w-4 h-4" />
                        </button>
                    </div>
                </template>
            </DataTable>
        </div>
    </div>
</template>
