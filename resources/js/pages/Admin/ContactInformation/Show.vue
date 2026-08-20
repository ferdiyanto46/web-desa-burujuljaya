<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeft, Edit } from 'lucide-vue-next';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { ContactInformation } from '@/types/contact-information';

const props = defineProps<{
    contact: ContactInformation;
}>();
</script>

<template>
    <Head :title="`Detail Kontak - ${contact.title}`" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <Link
                        :href="route('admin.contact-information.index')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-full transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5 text-gray-500" />
                    </Link>
                    <div>
                        <h2 class="text-2xl font-semibold tracking-tight">Detail Informasi Kontak</h2>
                        <p class="text-sm text-muted-foreground">
                            {{ contact.title }}
                        </p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <Button variant="outline" as-child>
                        <a :href="route('contact-information.show', contact.slug)" target="_blank">
                            Lihat di Publik
                        </a>
                    </Button>
                    <Button as-child>
                        <Link :href="route('admin.contact-information.edit', contact.ulid)">
                            <Edit class="w-4 h-4 mr-2" />
                            Edit Kontak
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="max-w-5xl mx-auto py-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kolom Utama -->
                <div class="md:col-span-2 space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Utama</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Judul</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ contact.title }}</p>
                            </div>
                            
                            <div v-if="contact.excerpt">
                                <h4 class="text-sm font-medium text-gray-500">Kutipan Singkat</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ contact.excerpt }}</p>
                            </div>

                            <div v-if="contact.description">
                                <h4 class="text-sm font-medium text-gray-500">Deskripsi Lengkap</h4>
                                <!-- eslint-disable-next-line vue/no-v-html -->
                                <div class="prose dark:prose-invert max-w-none mt-2" v-html="contact.description"></div>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Lokasi & Peta</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500">Alamat Lengkap</h4>
                                <p class="text-base text-gray-900 dark:text-white whitespace-pre-wrap">{{ contact.address || '-' }}</p>
                            </div>
                            
                            <div v-if="contact.google_maps_url">
                                <h4 class="text-sm font-medium text-gray-500">URL Google Maps</h4>
                                <a :href="contact.google_maps_url" target="_blank" class="text-blue-600 hover:underline break-all">
                                    {{ contact.google_maps_url }}
                                </a>
                            </div>

                            <div v-if="contact.google_maps_embed">
                                <h4 class="text-sm font-medium text-gray-500 mb-2">Embed Google Maps</h4>
                                <!-- eslint-disable-next-line vue/no-v-html -->
                                <div class="w-full overflow-hidden rounded-lg aspect-video" v-html="contact.google_maps_embed"></div>
                            </div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Kolom Samping -->
                <div class="space-y-6">
                    <Card>
                        <CardHeader>
                            <CardTitle>Status & Pengaturan</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Status</h4>
                                <Badge :variant="contact.status === 'published' ? 'default' : (contact.status === 'draft' ? 'secondary' : 'destructive')">
                                    {{ contact.status === 'published' ? 'Dipublikasi' : (contact.status === 'draft' ? 'Draft' : 'Diarsipkan') }}
                                </Badge>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 mb-1">Disematkan</h4>
                                <Badge v-if="contact.is_pinned" variant="outline" class="bg-blue-50 text-blue-600 border-blue-200">Ya</Badge>
                                <span v-else class="text-gray-900 dark:text-white">Tidak</span>
                            </div>

                            <div v-if="contact.sort_order">
                                <h4 class="text-sm font-medium text-gray-500">Urutan Tampil</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ contact.sort_order }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card v-if="contact.featured_image_path">
                        <CardHeader>
                            <CardTitle>Gambar Unggulan</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <img :src="`/storage/${contact.featured_image_path}`" :alt="contact.title" class="w-full rounded-lg shadow-sm" />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Informasi Kontak</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-4">
                            <div v-if="contact.phone || contact.mobile || contact.whatsapp">
                                <h4 class="text-sm font-medium text-gray-500">Telepon / HP / WA</h4>
                                <ul class="list-disc list-inside text-gray-900 dark:text-white mt-1">
                                    <li v-if="contact.phone">{{ contact.phone }} (Telepon)</li>
                                    <li v-if="contact.mobile">{{ contact.mobile }} (HP)</li>
                                    <li v-if="contact.whatsapp">{{ contact.whatsapp }} (WA)</li>
                                </ul>
                            </div>

                            <div v-if="contact.email">
                                <h4 class="text-sm font-medium text-gray-500">Email</h4>
                                <p class="text-base text-gray-900 dark:text-white">{{ contact.email }}</p>
                            </div>

                            <div v-if="contact.website">
                                <h4 class="text-sm font-medium text-gray-500">Website</h4>
                                <a :href="contact.website" target="_blank" class="text-blue-600 hover:underline">{{ contact.website }}</a>
                            </div>

                            <div v-if="contact.emergency_contact">
                                <h4 class="text-sm font-medium text-gray-500">Kontak Darurat</h4>
                                <p class="text-base text-gray-900 dark:text-white font-semibold text-red-600">{{ contact.emergency_contact }}</p>
                            </div>

                            <div v-if="contact.office_hours">
                                <h4 class="text-sm font-medium text-gray-500">Jam Kerja</h4>
                                <p class="text-base text-gray-900 dark:text-white whitespace-pre-wrap">{{ contact.office_hours }}</p>
                            </div>
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Sosial Media</CardTitle>
                        </CardHeader>
                        <CardContent class="space-y-3">
                            <div v-if="contact.facebook">
                                <h4 class="text-sm font-medium text-gray-500">Facebook</h4>
                                <a :href="contact.facebook" target="_blank" class="text-blue-600 hover:underline break-all">{{ contact.facebook }}</a>
                            </div>
                            <div v-if="contact.instagram">
                                <h4 class="text-sm font-medium text-gray-500">Instagram</h4>
                                <a :href="contact.instagram" target="_blank" class="text-blue-600 hover:underline break-all">{{ contact.instagram }}</a>
                            </div>
                            <div v-if="contact.youtube">
                                <h4 class="text-sm font-medium text-gray-500">YouTube</h4>
                                <a :href="contact.youtube" target="_blank" class="text-blue-600 hover:underline break-all">{{ contact.youtube }}</a>
                            </div>
                            <div v-if="contact.tiktok">
                                <h4 class="text-sm font-medium text-gray-500">TikTok</h4>
                                <a :href="contact.tiktok" target="_blank" class="text-blue-600 hover:underline break-all">{{ contact.tiktok }}</a>
                            </div>
                            <div v-if="!contact.facebook && !contact.instagram && !contact.youtube && !contact.tiktok">
                                <p class="text-gray-500 italic">Belum ada akun sosial media yang ditambahkan.</p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
