<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Plus, Edit, Trash2, Eye } from 'lucide-vue-next';
import { ref } from 'vue';
import DataTable from '@/components/tables/DataTable.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { ContactInformation } from '@/types/contact-information';

const props = defineProps<{
    contactInformations: {
        data: ContactInformation[];
        meta: any;
        links: any;
    };
}>();

const columns = [
    { key: 'title', label: 'Judul', sortable: true },
    { key: 'contact', label: 'Kontak Utama' },
    { key: 'status', label: 'Status' },
    { key: 'is_pinned', label: 'Disematkan', align: 'center' as const },
];

const deleteContact = (ulid: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus informasi kontak ini?')) {
        router.delete(route('admin.contact-information.destroy', ulid), {
            preserveScroll: true,
        });
    }
};
</script>

<template>
    <Head title="Manajemen Informasi Kontak" />

    <AdminLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-semibold tracking-tight">Informasi Kontak & Publik</h2>
                    <p class="text-sm text-muted-foreground">
                        Kelola data kontak, lokasi, dan informasi publik desa.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Button as-child>
                        <Link :href="route('admin.contact-information.create')">
                            <Plus class="w-4 h-4 mr-2" />
                            Tambah Kontak
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="space-y-6">
            <Card>
                <CardHeader>
                    <CardTitle>Daftar Informasi Kontak</CardTitle>
                    <CardDescription>
                        Semua informasi kontak publik yang tersedia.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <DataTable
                        :columns="columns"
                        :items="contactInformations.data"
                    >
                        <template #cell-title="{ item }">
                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ item.title }}</div>
                            <div class="text-xs text-gray-500 line-clamp-1 mt-1 max-w-xs">{{ item.address || 'Alamat belum diisi' }}</div>
                        </template>

                        <template #cell-contact="{ item }">
                            <div class="text-sm flex flex-col gap-1">
                                <span v-if="item.phone || item.mobile">📞 {{ item.phone || item.mobile }}</span>
                                <span v-if="item.email">✉️ {{ item.email }}</span>
                                <span v-if="!item.phone && !item.mobile && !item.email" class="text-gray-400 italic">Belum diisi</span>
                            </div>
                        </template>

                        <template #cell-status="{ item }">
                            <Badge :variant="item.status === 'published' ? 'default' : (item.status === 'draft' ? 'secondary' : 'destructive')">
                                {{ item.status === 'published' ? 'Dipublikasi' : (item.status === 'draft' ? 'Draft' : 'Diarsipkan') }}
                            </Badge>
                        </template>

                        <template #cell-is_pinned="{ item }">
                            <Badge v-if="item.is_pinned" variant="outline" class="bg-blue-50 text-blue-600 border-blue-200">
                                Ya
                            </Badge>
                            <span v-else class="text-gray-400">-</span>
                        </template>

                        <template #actions="{ item }">
                            <div class="flex justify-end gap-2">
                                <Button variant="outline" size="sm" as-child>
                                    <a :href="route('contact-information.show', item.slug)" target="_blank" title="Lihat">
                                        <Eye class="w-4 h-4" />
                                    </a>
                                </Button>
                                <Button variant="outline" size="sm" as-child>
                                    <Link :href="route('admin.contact-information.edit', item.ulid)" title="Edit">
                                        <Edit class="w-4 h-4" />
                                    </Link>
                                </Button>
                                <Button variant="destructive" size="sm" @click="deleteContact(item.ulid)" title="Hapus">
                                    <Trash2 class="w-4 h-4" />
                                </Button>
                            </div>
                        </template>
                    </DataTable>
                </CardContent>
            </Card>
        </div>
    </AdminLayout>
</template>
