<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { Image as ImageIcon, Plus, Tag } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { Product, ProductCategory } from '@/types/product'

const props = defineProps<{
    products: {
        data: Product[]
        meta: any
        links: any
    }
    categories: {
        data: ProductCategory[]
    }
    filters: {
        search?: string
        category_id?: string
        is_active?: string
    }
}>()
</script>

<template>
    <Head title="Manajemen Produk Desa" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Manajemen Produk Desa</h2>
                    <p class="text-sm text-muted-foreground">
                        Kelola data produk desa yang dihasilkan oleh UMKM.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <Link :href="route('admin.product-categories.index')">Kategori Produk</Link>
                    </Button>
                    <Button as-child>
                        <Link :href="route('admin.products.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Tambah Produk
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Daftar Produk</CardTitle>
                    <CardDescription>
                        Semua data produk yang telah didaftarkan.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Produk</TableHead>
                                    <TableHead>Kategori</TableHead>
                                    <TableHead>Harga</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="item in products.data" :key="item.ulid">
                                    <TableCell>
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-lg bg-muted flex items-center justify-center overflow-hidden flex-shrink-0">
                                                <img v-if="item.featured_image_path" :src="`/storage/${item.featured_image_path}`" class="w-full h-full object-cover" />
                                                <ImageIcon v-else class="w-6 h-6 text-muted-foreground" />
                                            </div>
                                            <div>
                                                <span class="font-medium block truncate max-w-[200px]">{{ item.title }}</span>
                                                <div class="flex items-center text-xs text-muted-foreground mt-1 gap-2">
                                                    <span v-if="item.umkm" class="flex items-center">
                                                        <Tag class="w-3 h-3 mr-1" />
                                                        {{ item.umkm.title }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        {{ item.category?.name || '-' }}
                                    </TableCell>
                                    <TableCell>
                                        Rp {{ new Intl.NumberFormat('id-ID').format(item.price) }}
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="item.status === 'published' ? 'default' : 'secondary'">
                                            {{ item.status === 'published' ? 'Aktif' : 'Nonaktif' }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <Button variant="outline" size="sm" as-child>
                                                <Link :href="route('admin.products.edit', item.ulid)">Edit</Link>
                                            </Button>
                                            <Button variant="outline" size="sm" as-child>
                                                <a :href="route('products.show', item.slug)" target="_blank">Lihat</a>
                                            </Button>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="products.data.length === 0">
                                    <TableCell colspan="5" class="text-center h-24 text-muted-foreground">
                                        Belum ada data produk.
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
