<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Icons } from '@/constants/icons';
import AdminLayout from '@/layouts/AdminLayout.vue';

const clearCache = (type: string) => {
    if (confirm(`Apakah Anda yakin ingin membersihkan cache ${type}?`)) {
        router.post(route('admin.cache.clear'), { type });
    }
};

const cacheOptions = [
    {
        id: 'application',
        name: 'Application Cache',
        description: 'Membersihkan cache aplikasi umum (Data, Objek, dsb).',
        icon: Icons.DatabaseIcon,
    },
    {
        id: 'views',
        name: 'Views Cache',
        description: 'Membersihkan cache hasil render tampilan Blade.',
        icon: Icons.MonitorIcon,
    },
    {
        id: 'routes',
        name: 'Routes Cache',
        description: 'Membersihkan cache rute aplikasi.',
        icon: Icons.RouteIcon,
    },
    {
        id: 'config',
        name: 'Config Cache',
        description: 'Membersihkan cache konfigurasi aplikasi.',
        icon: Icons.SettingsIcon,
    },
];
</script>

<template>
    <Head title="Manajemen Cache" />

    <AdminLayout>
        <div class="space-y-6">
            <PageTitle 
                title="Manajemen Cache" 
                description="Kelola dan bersihkan cache sistem untuk memastikan aplikasi berjalan dengan data terbaru."
            >
                <div class="mt-4 sm:mt-0">
                    <Button variant="destructive" @click="clearCache('all')">
                        <component :is="Icons.TrashIcon" class="w-4 h-4 mr-2" />
                        Bersihkan Semua Cache
                    </Button>
                </div>
            </PageTitle>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div 
                    v-for="option in cacheOptions" 
                    :key="option.id"
                    class="bg-card rounded-xl border border-border p-6 flex flex-col sm:flex-row gap-4 items-start sm:items-center justify-between hover:border-primary/50 transition-colors shadow-sm"
                >
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-lg bg-primary/10 text-primary shrink-0">
                            <component :is="option.icon" class="w-6 h-6" />
                        </div>
                        <div>
                            <h3 class="font-medium text-base">{{ option.name }}</h3>
                            <p class="text-sm text-muted-foreground mt-1">{{ option.description }}</p>
                        </div>
                    </div>
                    
                    <Button variant="outline" @click="clearCache(option.id)" class="w-full sm:w-auto shrink-0 mt-2 sm:mt-0">
                        Bersihkan
                    </Button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
