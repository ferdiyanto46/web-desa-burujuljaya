<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { id } from 'date-fns/locale'
import { MapPin, Image as ImageIcon, Map, Star, Plus } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { Tourism, TourismCategory } from '@/types/tourism'

const props = defineProps<{
    tourisms: {
        data: Tourism[]
        meta: any
        links: any
    }
    categories: {
        data: TourismCategory[]
    }
    filters: {
        search?: string
        status?: string
        category_id?: string
    }
}>()
</script>

<template>
    <Head title="Manajemen Wisata" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Manajemen Wisata</h2>
                    <p class="text-sm text-muted-foreground">
                        Kelola destinasi wisata desa, kategori, dan fasilitas.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.tourism-categories.index')">Kategori</Link>
                    </Button>
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.tourism-facilities.index')">Fasilitas</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('admin.tourisms.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Tambah Wisata
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Daftar Destinasi Wisata</CardTitle>
                    <CardDescription>
                        Semua destinasi wisata yang telah ditambahkan.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Destinasi</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Visibilitas</TableHead>
                                    <TableHead>Fasilitas</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in tourisms.data" :key="item.id">
                                    <TableCell>
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-muted flex items-center justify-center overflow-hidden flex-shrink-0">
                                                <img v-if="item.featured_image_url" :src="item.featured_image_url" class="w-full h-full object-cover" />
                                                <ImageIcon v-else class="w-6 h-6 text-muted-foreground" />
                                            </div>
                                            <div>
                                                <p class="font-medium line-clamp-1">{{ item.title }}</p>
                                                <div class="flex items-center text-xs text-muted-foreground mt-1 gap-2">
                                                    <span class="flex items-center">
                                                        <MapPin class="w-3 h-3 mr-1" />
                                                        {{ item.address ? 'Tersedia' : 'Belum diisi' }}
                                                    </span>
                                                    <span v-if="item.google_maps_url" class="flex items-center text-blue-500">
                                                        <Map class="w-3 h-3 mr-1" />
                                                        Maps
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
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
                                    <TableCell>
                                        <div class="flex gap-1 flex-wrap max-w-[150px]">
                                            <Badge v-for="facility in item.facilities" :key="facility.id" variant="secondary" class="text-[10px]">
                                                {{ facility.name }}
                                            </Badge>
                                        </div>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('admin.tourisms.edit', item.ulid)">Edit</Link>
                                            </Button>
                                            <Button variant="outline" size="sm" as-child>
                                                <a :href="route('tourism.show', item.slug)" target="_blank">Lihat</a>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="tourisms.data.length === 0">
                                    <TableCell colspan="6" class="text-center h-24 text-muted-foreground">
                                        Belum ada destinasi wisata.
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
