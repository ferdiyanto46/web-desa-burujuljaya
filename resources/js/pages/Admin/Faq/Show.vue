<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { Faq } from '@/types/faq';

defineProps<{
    faq: { data: Faq };
}>();

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
</script>

<template>
    <AdminLayout>
        <Head :title="`Detail FAQ: ${faq.data.question}`" />

        <div class="space-y-6 max-w-5xl mx-auto pb-12">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('admin.faqs.index')">
                        <Button variant="ghost" size="sm" class="h-9 w-9 p-0 text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <ArrowLeft class="w-5 h-5" />
                        </Button>
                    </Link>
                    <PageTitle title="Detail FAQ" :description="faq.data.question" />
                </div>

                <div class="flex items-center gap-2">
                    <Link :href="route('admin.faqs.edit', faq.data.ulid)">
                        <Button variant="default" class="bg-emerald-600 hover:bg-emerald-700 text-white flex items-center gap-2">
                            <Edit class="w-4 h-4" />
                            <span>Edit FAQ</span>
                        </Button>
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                <!-- Content (Left) -->
                <div class="md:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle class="text-xl leading-relaxed">{{ faq.data.question }}</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="prose max-w-none dark:prose-invert text-muted-foreground" v-html="faq.data.answer"></div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Sidebar (Right) -->
                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Metadata</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Status Publikasi</h4>
                                <Badge :variant="getStatusVariant(faq.data.status)">
                                    {{ getStatusLabel(faq.data.status) }}
                                </Badge>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Sorotan</h4>
                                <Badge v-if="faq.data.is_featured" variant="secondary">Ya</Badge>
                                <span v-else class="text-sm text-foreground">Tidak</span>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Kategori</h4>
                                <p class="text-sm text-foreground">{{ faq.data.category || '-' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Dibuat Oleh</h4>
                                <p class="text-sm text-foreground">{{ faq.data.author?.name || '-' }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Jumlah Dilihat</h4>
                                <p class="text-sm text-foreground">{{ faq.data.view_count.toLocaleString('id-ID') }} kali</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Urutan (Sort Order)</h4>
                                <p class="text-sm text-foreground">{{ faq.data.sort_order }}</p>
                            </div>

                            <div v-if="faq.data.published_at">
                                <h4 class="text-sm font-medium text-muted-foreground mb-1">Tanggal Terbit</h4>
                                <p class="text-sm text-foreground">{{ new Date(faq.data.published_at).toLocaleString('id-ID') }}</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
