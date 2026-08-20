<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Icons } from '@/constants/icons';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineProps<{
    backups: Array<{
        path: string;
        file_name: string;
        size: number;
        date: number;
    }>;
}>();

const formatBytes = (bytes: number, decimals = 2) => {
    if (!+bytes) {
return '0 Bytes';
}

    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};

const formatDate = (timestamp: number) => {
    return new Date(timestamp * 1000).toLocaleString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const createBackup = (onlyDatabase = false) => {
    if (confirm(`Apakah Anda yakin ingin membuat backup ${onlyDatabase ? 'database saja' : 'penuh'}? Proses ini mungkin membutuhkan waktu.`)) {
        router.post(route('admin.backups.store'), { only_database: onlyDatabase });
    }
};

const downloadBackup = (fileName: string) => {
    window.location.href = route('admin.backups.download', fileName);
};

const deleteBackup = (fileName: string) => {
    if (confirm('Apakah Anda yakin ingin menghapus backup ini? Tindakan ini tidak dapat dibatalkan.')) {
        router.delete(route('admin.backups.destroy', fileName));
    }
};
</script>

<template>
    <Head title="Manajemen Backup" />

    <AdminLayout>
        <div class="space-y-6">
            <PageTitle 
                title="Manajemen Backup" 
                description="Kelola pencadangan data dan file sistem."
            >
                <div class="flex gap-2 mt-4 sm:mt-0">
                    <Button variant="outline" @click="createBackup(true)">
                        <component :is="Icons.DatabaseIcon" class="w-4 h-4 mr-2" />
                        Backup Database
                    </Button>
                    <Button @click="createBackup(false)">
                        <component :is="Icons.ArchiveIcon" class="w-4 h-4 mr-2" />
                        Backup Penuh
                    </Button>
                </div>
            </PageTitle>

            <div class="bg-card rounded-xl border border-border overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
                            <tr>
                                <th class="px-6 py-4 font-medium">Nama File</th>
                                <th class="px-6 py-4 font-medium">Ukuran</th>
                                <th class="px-6 py-4 font-medium">Tanggal Dibuat</th>
                                <th class="px-6 py-4 font-medium text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="backup in backups" :key="backup.file_name" class="hover:bg-muted/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap font-medium flex items-center gap-2">
                                    <component :is="Icons.FileArchiveIcon" class="w-4 h-4 text-primary" />
                                    {{ backup.file_name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ formatBytes(backup.size) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ formatDate(backup.date) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                    <Button variant="outline" size="sm" @click="downloadBackup(backup.file_name)">
                                        <component :is="Icons.DownloadIcon" class="w-4 h-4" />
                                        <span class="sr-only">Unduh</span>
                                    </Button>
                                    <Button variant="destructive" size="sm" @click="deleteBackup(backup.file_name)">
                                        <component :is="Icons.TrashIcon" class="w-4 h-4" />
                                        <span class="sr-only">Hapus</span>
                                    </Button>
                                </td>
                            </tr>
                            <tr v-if="backups.length === 0">
                                <td colspan="4" class="px-6 py-8 text-center text-muted-foreground">
                                    Tidak ada file backup yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
