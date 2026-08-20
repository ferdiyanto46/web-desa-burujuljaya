<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import { ArrowLeft, Edit, ExternalLink, Image as ImageIcon, Tag, Store } from 'lucide-vue-next'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card'
import AdminLayout from '@/layouts/AdminLayout.vue'
import type { Product } from '@/types/product'

const props = defineProps<{
    product: Product
}>()
</script>

<template>
    <Head :title="`Detail Produk: ${product.title}`" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="route('admin.products.index')">
                        <ArrowLeft class="w-4 h-4" />
                    </Link>
                </Button>
                <div class="flex-1 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight">Detail Produk</h2>
                        <p class="text-sm text-muted-foreground">
                            Lihat informasi lengkap tentang produk {{ product.title }}.
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <Button variant="outline" as-child>
                            <a :href="route('products.show', product.slug)" target="_blank">
                                <ExternalLink class="w-4 h-4 mr-2" />
                                Lihat di Web
                            </a>
                        </Button>
                        <Button as-child>
                            <Link :href="route('admin.products.edit', product.ulid)">
                                <Edit class="w-4 h-4 mr-2" />
                                Edit Produk
                            </Link>
                        </Button>
                    </div>
                </div>
            </div>
        </template>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Kolom Utama -->
            <div class="md:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Informasi Dasar</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div>
                            <h3 class="text-lg font-medium">{{ product.title }}</h3>
                            <div class="mt-1 flex gap-2">
                                <Badge variant="outline" class="flex items-center gap-1">
                                    <Tag class="w-3 h-3" />
                                    {{ product.category?.name || 'Kategori tidak diketahui' }}
                                </Badge>
                                <Badge :variant="product.status === 'published' ? 'default' : 'secondary'">
                                    {{ product.status === 'published' ? 'Aktif' : 'Nonaktif' }}
                                </Badge>
                            </div>
                        </div>

                        <div>
                            <p class="text-sm text-muted-foreground">Harga</p>
                            <p class="text-xl font-bold">Rp {{ new Intl.NumberFormat('id-ID').format(product.price) }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-muted-foreground mb-2">Deskripsi Produk</p>
                            <div class="prose max-w-none prose-sm dark:prose-invert border rounded-md p-4 bg-muted/50" v-html="product.description"></div>
                        </div>
                    </CardContent>
                </Card>

                <Card v-if="product.photos && product.photos.length > 0">
                    <CardHeader>
                        <CardTitle>Galeri Produk</CardTitle>
                        <CardDescription>Foto-foto tambahan dari produk ini</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div v-for="photo in product.photos" :key="photo.id" class="rounded-md overflow-hidden aspect-square">
                                <img :src="`/storage/${photo.image_path}`" :alt="photo.caption || product.title" class="w-full h-full object-cover" />
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Kolom Samping -->
            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>UMKM Terkait</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-primary/10 text-primary rounded-md">
                                <Store class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="font-medium">{{ product.umkm?.title || 'UMKM Tidak Diketahui' }}</h4>
                                <Link v-if="product.umkm" :href="route('admin.umkm.edit', product.umkm.ulid)" class="text-sm text-primary hover:underline">
                                    Lihat Profil UMKM
                                </Link>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Gambar Utama</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="rounded-md overflow-hidden bg-muted aspect-video flex items-center justify-center">
                            <img v-if="product.featured_image_path" :src="`/storage/${product.featured_image_path}`" :alt="product.title" class="w-full h-full object-cover" />
                            <div v-else class="text-center text-muted-foreground">
                                <ImageIcon class="w-12 h-12 mx-auto mb-2 opacity-50" />
                                <p class="text-sm">Tidak ada gambar utama</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Metadata</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Dibuat pada:</span>
                            <span>{{ product.created_at ? new Date(product.created_at).toLocaleDateString('id-ID') : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">Diperbarui pada:</span>
                            <span>{{ product.updated_at ? new Date(product.updated_at).toLocaleDateString('id-ID') : '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-muted-foreground">ULID:</span>
                            <span class="font-mono text-xs">{{ product.ulid }}</span>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AdminLayout>
</template>
