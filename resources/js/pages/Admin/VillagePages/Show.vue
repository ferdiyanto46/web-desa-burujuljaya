<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, ExternalLinkIcon, PencilIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { VillagePage } from '@/types/village-page';

defineOptions({ layout: AdminLayout });

defineProps<{
    page: { data: VillagePage };
}>();
</script>

<template>
    <Head :title="`Detail Halaman: ${page.data.title}`" />

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <Button variant="ghost" size="icon" as-child>
                    <Link :href="route('admin.village-pages.index')">
                        <ArrowLeftIcon class="w-4 h-4" />
                        <span class="sr-only">Kembali</span>
                    </Link>
                </Button>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight">Detail Halaman Desa</h2>
                    <p class="text-muted-foreground">{{ page.data.title }}</p>
                </div>
            </div>
            
            <div class="flex gap-2">
                <Button variant="outline" as-child v-if="page.data.status === 'published'">
                    <a :href="route('profile.show', page.data.slug)" target="_blank">
                        <ExternalLinkIcon class="w-4 h-4 mr-2" />
                        Lihat Publik
                    </a>
                </Button>
                <Button as-child>
                    <Link :href="route('admin.village-pages.edit', page.data.ulid)">
                        <PencilIcon class="w-4 h-4 mr-2" />
                        Edit Halaman
                    </Link>
                </Button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Konten Halaman</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div v-if="page.data.featured_image_url" class="aspect-video relative rounded-md overflow-hidden bg-muted mb-4">
                            <img :src="page.data.featured_image_url" :alt="page.data.title" class="object-cover w-full h-full" />
                        </div>
                        
                        <div class="space-y-2">
                            <h3 class="text-xl font-semibold">{{ page.data.title }}</h3>
                            <p v-if="page.data.excerpt" class="text-muted-foreground italic border-l-4 border-primary pl-4 py-1">
                                {{ page.data.excerpt }}
                            </p>
                        </div>
                        
                        <div class="prose dark:prose-invert max-w-none" v-html="page.data.content"></div>
                    </CardContent>
                </Card>
            </div>
            
            <div class="space-y-6">
                <Card>
                    <CardHeader>
                        <CardTitle>Informasi Halaman</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-2 gap-y-4 text-sm">
                            <div class="text-muted-foreground">Tipe Halaman</div>
                            <div class="font-medium text-right">{{ page.data.page_type_label }}</div>
                            
                            <div class="text-muted-foreground">Status</div>
                            <div class="text-right">
                                <span
                                    class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full"
                                    :class="{
                                        'bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400': page.data.status === 'published',
                                        'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400': page.data.status === 'draft',
                                        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400': page.data.status === 'archived',
                                    }"
                                >
                                    {{ page.data.status_label }}
                                </span>
                            </div>
                            
                            <div class="text-muted-foreground">URL Slug</div>
                            <div class="font-medium text-right break-all">{{ page.data.slug }}</div>
                            
                            <div class="text-muted-foreground">Urutan</div>
                            <div class="font-medium text-right">{{ page.data.sort_order }}</div>
                            
                            <div class="text-muted-foreground">Disematkan</div>
                            <div class="font-medium text-right">{{ page.data.is_pinned ? 'Ya' : 'Tidak' }}</div>
                            
                            <div class="text-muted-foreground">Tanggal Terbit</div>
                            <div class="font-medium text-right">{{ page.data.published_at || '-' }}</div>
                            
                            <div class="text-muted-foreground">Penulis</div>
                            <div class="font-medium text-right">{{ page.data.author?.name || '-' }}</div>
                            
                            <div class="text-muted-foreground">Dibuat Pada</div>
                            <div class="font-medium text-right">{{ page.data.created_at }}</div>
                            
                            <div class="text-muted-foreground">Terakhir Diperbarui</div>
                            <div class="font-medium text-right">{{ page.data.updated_at }}</div>
                        </div>
                    </CardContent>
                </Card>
                
                <Card>
                    <CardHeader>
                        <CardTitle>SEO Metadata</CardTitle>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <div class="grid grid-cols-1 gap-y-4 text-sm">
                            <div>
                                <div class="text-muted-foreground mb-1">Meta Title</div>
                                <div class="font-medium">{{ page.data.meta_title || '-' }}</div>
                            </div>
                            <div>
                                <div class="text-muted-foreground mb-1">Meta Description</div>
                                <div class="font-medium">{{ page.data.meta_description || '-' }}</div>
                            </div>
                            <div>
                                <div class="text-muted-foreground mb-1">Canonical URL</div>
                                <div class="font-medium break-all">{{ page.data.canonical_url || '-' }}</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
