<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { PlusIcon, EditIcon, TrashIcon } from 'lucide-vue-next'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { PaginationData } from '@/types'
import type { UmkmCategory } from '@/types/umkm'

const props = defineProps<{
    categories: PaginationData<UmkmCategory>
    filters?: {
        search?: string
    }
}>()

const destroy = (id: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus kategori UMKM ini?')) {
        router.delete(route('admin.umkm-categories.destroy', id))
    }
}
</script>

<template>
    <Head title="Manajemen Kategori UMKM" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Kategori UMKM</h2>
                    <p class="text-sm text-muted-foreground">
                        Kelola kategori UMKM desa.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.umkm.index')">Kembali ke UMKM</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('admin.umkm-categories.create')">
                            <PlusIcon class="w-4 h-4 mr-2" />
                            Tambah Kategori
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Daftar Kategori UMKM</CardTitle>
                    <CardDescription>
                        Semua kategori UMKM yang telah ditambahkan.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Nama Kategori</TableHead>
                                    <TableHead>Slug</TableHead>
                                    <TableHead>Deskripsi</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in categories.data" :key="item.id">
                                    <TableCell class="font-medium">
                                        {{ item.name }}
                                    </TableCell>
                                    <TableCell>
                                        {{ item.slug }}
                                    </TableCell>
                                    <TableCell>
                                        {{ item.description || '-' }}
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('admin.umkm-categories.edit', item.ulid)">
                                                    <EditIcon class="w-4 h-4" />
                                                </Link>
                                            </Button>
                                            <Button variant="destructive" size="sm" @click="destroy(item.ulid)">
                                                <TrashIcon class="w-4 h-4" />
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="categories.data.length === 0">
                                    <TableCell colspan="4" class="text-center h-24 text-muted-foreground">
                                        Belum ada kategori UMKM.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
