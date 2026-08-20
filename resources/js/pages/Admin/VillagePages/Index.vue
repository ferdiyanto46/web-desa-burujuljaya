<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { PlusIcon, PencilIcon, TrashIcon, RotateCcwIcon, ArchiveXIcon } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import type { PaginatedData } from '@/types/pagination';
import type { VillagePage } from '@/types/village-page';

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric'
    }).format(new Date(dateString));
};

const props = defineProps<{
    pages: PaginatedData<VillagePage>;
    filters: {
        search?: string;
    };
}>();

const handleSearch = (e: Event) => {
    const target = e.target as HTMLInputElement;
    router.get(
        route('admin.village-pages.index'),
        { search: target.value },
        { preserveState: true, preserveScroll: true, replace: true }
    );
};

const handleDelete = (page: VillagePage) => {
    if (confirm(`Apakah Anda yakin ingin menghapus halaman "${page.title}"?`)) {
        router.delete(route('admin.village-pages.destroy', page.ulid));
    }
};

const handleRestore = (page: VillagePage) => {
    if (confirm(`Apakah Anda yakin ingin memulihkan halaman "${page.title}"?`)) {
        router.post(route('admin.village-pages.restore', page.ulid));
    }
};

const handleForceDelete = (page: VillagePage) => {
    if (confirm(`Peringatan: Halaman "${page.title}" akan dihapus permanen! Lanjutkan?`)) {
        router.delete(route('admin.village-pages.force-delete', page.ulid));
    }
};
</script>

<template>
    <Head title="Kelola Halaman Desa" />

    <DashboardContainer>
        <template #header>
            <div class="flex items-center justify-between">
                <PageTitle 
                    title="Halaman Desa" 
                    description="Kelola profil dan halaman informasi desa."
                />
                <Button as-child>
                    <Link :href="route('admin.village-pages.create')">
                        <PlusIcon class="w-4 h-4 mr-2" />
                        Tambah Halaman
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
                            placeholder="Cari halaman..." 
                            :defaultValue="filters.search"
                            @keyup.enter="handleSearch"
                        />
                    </div>
                    
                    <div class="rounded-md border">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>Judul & Tipe</TableHead>
                                    <TableHead>Status</TableHead>
                                    <TableHead>Tanggal Terbit</TableHead>
                                    <TableHead class="text-right">Aksi</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="page in pages.data" :key="page.ulid">
                                    <TableCell>
                                        <div class="flex flex-col">
                                            <span class="font-medium" :class="{'line-through text-muted-foreground': page.deleted_at}">
                                                {{ page.title }}
                                            </span>
                                            <span class="text-xs text-muted-foreground">{{ page.page_type_label }}</span>
                                        </div>
                                    </TableCell>
                                    <TableCell>
                                        <Badge :variant="page.deleted_at ? 'destructive' : (page.status === 'published' ? 'default' : 'secondary')">
                                            {{ page.deleted_at ? 'Dihapus' : page.status_label }}
                                        </Badge>
                                    </TableCell>
                                    <TableCell>
                                        <span class="text-sm">
                                            {{ page.published_at ? formatDate(page.published_at) : '-' }}
                                        </span>
                                    </TableCell>
                                    <TableCell class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <template v-if="!page.deleted_at">
                                                <Button variant="outline" size="sm" as-child>
                                                    <Link :href="route('admin.village-pages.edit', page.ulid)">
                                                        Edit
                                                    </Link>
                                                </Button>
                                                <Button variant="outline" size="sm" class="text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="handleDelete(page)">
                                                    Hapus
                                                </Button>
                                            </template>
                                            <template v-else>
                                                <Button variant="outline" size="sm" @click="handleRestore(page)">
                                                    Pulihkan
                                                </Button>
                                                <Button variant="outline" size="sm" class="text-destructive hover:bg-destructive hover:text-destructive-foreground" @click="handleForceDelete(page)">
                                                    Hapus Permanen
                                                </Button>
                                            </template>
                                        </div>
                                    </TableCell>
                                </TableRow>
                                <TableRow v-if="pages.data.length === 0">
                                    <TableCell colspan="4" class="text-center h-24 text-muted-foreground">
                                        Belum ada halaman.
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
