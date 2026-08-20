<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { PaginatedData } from '@/types/pagination';
import type { VillageService } from '@/types/village-service';

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};

defineProps<{
    villageServices: PaginatedData<VillageService>;
    filters: {
        search?: string;
    };
}>();

const handleSearch = (e: Event) => {
    const target = e.target as HTMLInputElement;
    router.get(
        route('admin.village-services.index'),
        { search: target.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
};

const handleDelete = (service: VillageService) => {
    if (confirm(`Apakah Anda yakin ingin menghapus layanan "${service.title}"?`)) {
        router.delete(route('admin.village-services.destroy', service.ulid));
    }
};

const handleRestore = (service: VillageService) => {
    if (confirm(`Apakah Anda yakin ingin memulihkan layanan "${service.title}"?`)) {
        router.post(route('admin.village-services.restore', service.ulid));
    }
};

const handleForceDelete = (service: VillageService) => {
    if (confirm(`Peringatan: Layanan "${service.title}" akan dihapus permanen! Lanjutkan?`)) {
        router.delete(route('admin.village-services.force-delete', service.ulid));
    }
};
</script>

<template>
    <Head title="Kelola Layanan Desa" />

    <DashboardContainer>
        <template #header>
            <div class="flex items-center justify-between">
                <PageTitle 
                    title="Layanan Desa" 
                    description="Kelola informasi layanan administrasi desa."
                />
                <Button as-child>
                    <Link :href="route('admin.village-services.create')">
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Tambah Layanan
                    </Link>
                </Button>
            </div>
        </template>

        <div class="space-y-6 mt-6">
            <Card>
                <CardContent class="pt-6">
                    <div class="mb-6 max-w-sm">
                        <Input 
                            type="search" 
                            placeholder="Cari layanan..." 
                            :defaultValue="filters.search"
                            @keyup.enter="handleSearch"
                        />
                    </div>
                    
                    <div class="rounded-md border overflow-x-auto">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Layanan</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Tanggal Terbit</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="service in villageServices.data" :key="service.ulid">
                                    <TableCell>
                                        <div class="flex flex-col">
                                            <span class="font-medium" :class="{'line-through text-muted-foreground': service.timestamps?.deleted_at}">
                                                {{ service.title }}
                                            </span>
                                            <span class="text-xs text-muted-foreground flex items-center gap-2">
                                                <span v-if="service.is_pinned" class="bg-amber-100 text-amber-800 px-1.5 py-0.5 rounded text-[10px] font-medium">Disematkan</span>
                                                <span v-if="service.downloadable_form_url" class="text-blue-600">Ada Lampiran</span>
                                            </span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="service.timestamps?.deleted_at ? 'destructive' : (service.status === 'published' ? 'default' : 'secondary')">
                                            {{ service.timestamps?.deleted_at ? 'Dihapus' : (service.status === 'published' ? 'Diterbitkan' : (service.status === 'draft' ? 'Draf' : 'Arsip')) }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span class="text-sm">
                                            {{ service.published_at ? formatDate(service.published_at) : '-' }}
                                        </span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <template v-if="!service.timestamps?.deleted_at">
                                                <Button variant="outline" size="sm" as-child>
                                                    <Link :href="route('admin.village-services.show', service.ulid)">
                                                        Lihat
                                                    </Link>
                                                </Button>
                                                <Button variant="outline" size="sm" as-child>
                                                    <Link :href="route('admin.village-services.edit', service.ulid)">
                                                        Edit
                                                    </Link>
                                                </Button>
                                                <Button variant="outline" size="sm" class="text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="handleDelete(service)">
                                                    Hapus
                                                </Button>
                                            </template>
                                            <template v-else>
                                                <Button variant="outline" size="sm" @click="handleRestore(service)">
                                                    Pulihkan
                                                </Button>
                                                <Button variant="outline" size="sm" class="text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="handleForceDelete(service)">
                                                    Hapus Permanen
                                                </Button>
                                            </template>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="villageServices.data.length === 0">
                                    <TableCell colspan="4" class="text-center h-24 text-muted-foreground">
                                        Belum ada layanan.
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </DashboardContainer>
</template>
