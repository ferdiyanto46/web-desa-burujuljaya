<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { MapPin, Image as ImageIcon, Star, Plus } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { Umkm, UmkmCategory } from '@/types/umkm'

const props = defineProps<{
    umkms: {
        data: Umkm[]
        meta: any
        links: any
    }
    categories: {
        data: UmkmCategory[]
    }
    filters: {
        search?: string
        status?: string
        category_id?: string
    }
}>()
</script>

<template>
    <Head title="Manajemen UMKM" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Manajemen UMKM</h2>
                    <p class="text-sm text-muted-foreground">
                        Kelola data Usaha Mikro, Kecil, dan Menengah desa.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.umkm-categories.index')">Kategori UMKM</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('admin.umkm.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Tambah UMKM
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Daftar UMKM</CardTitle>
                    <CardDescription>
                        Semua data UMKM yang telah didaftarkan.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>UMKM</TableHead>
                                    <TableHead>Pemilik</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Visibilitas</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in umkms.data" :key="item.id">
                                    <TableCell>
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-muted flex items-center justify-center overflow-hidden flex-shrink-0">
                                                <img v-if="item.featured_image_path" :src="`/storage/${item.featured_image_path}`" class="w-full h-full object-cover" />
                                                <ImageIcon v-else class="w-6 h-6 text-muted-foreground" />
                                            </div>
                                            <div>
                                                <p class="font-medium line-clamp-1">{{ item.title }}</p>
                                                <div class="flex items-center text-xs text-muted-foreground mt-1 gap-2">
                                                    <span class="flex items-center">
                                                        <MapPin class="w-3 h-3 mr-1" />
                                                        {{ item.address ? 'Alamat tersedia' : 'Belum diisi' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        {{ item.owner_name || '-' }}
                                    </TableCell>
                                    <TableCell>
                                        {{ item.category?.name || '-' }}
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="item.status === 'published' ? 'default' : (item.status === 'draft' ? 'secondary' : 'destructive')">
                                            {{ item.status === 'published' ? 'Dipublikasi' : (item.status === 'draft' ? 'Draft' : 'Diarsipkan') }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <div class="flex gap-1 flex-col items-start">
                                            <Badge v-if="item.is_featured" variant="outline" class="bg-amber-50 text-amber-600 border-amber-200">
                                                <Star class="w-3 h-3 mr-1 fill-amber-500" /> Unggulan
                                            </Badge>
                                            <Badge v-if="item.is_pinned" variant="outline" class="bg-blue-50 text-blue-600 border-blue-200">
                                                Disematkan
                                            </Badge>
                                        </div>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('admin.umkm.edit', item.ulid)">Edit</Link>
                                            </Button>
                                            <Button variant="outline" size="sm" as-child>
                                                <a :href="route('umkm.show', item.slug)" target="_blank">Lihat</a>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="umkms.data.length === 0">
                                    <TableCell colspan="6" class="text-center h-24 text-muted-foreground">
                                        Belum ada data UMKM.
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
