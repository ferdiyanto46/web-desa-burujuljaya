<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { debounce } from 'lodash-es';
import { ref, watch } from 'vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Icons } from '@/constants/icons';
import AdminLayout from '@/layouts/AdminLayout.vue';

const props = defineProps<{
    logs: {
        data: Array<{
            id: string;
            ulid: string;
            event: string;
            description: string;
            ip_address: string;
            created_at: string;
            causer?: {
                name: string;
            };
        }>;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
        current_page: number;
        last_page: number;
        from: number;
        to: number;
        total: number;
    };
    filters: {
        search?: string;
    };
}>();

const search = ref(props.filters.search || '');

watch(search, debounce((value) => {
    router.get(
        route('admin.activity-logs.index'),
        { search: value },
        { preserveState: true, replace: true }
    );
}, 300));

const getEventColor = (event: string) => {
    switch (event) {
        case 'created':
        case 'login':
            return 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400';
        case 'updated':
            return 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400';
        case 'deleted':
        case 'forceDeleted':
        case 'logout':
            return 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400';
        case 'restored':
            return 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400';
        case 'failed_login':
            return 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400';
        default:
            return 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
    }
};
</script>

<template>
    <Head title="Log Aktivitas" />

    <AdminLayout>
        <div class="space-y-6">
            <PageTitle 
                title="Log Aktivitas" 
                description="Catatan aktivitas sistem dan perubahan data."
            />

            <!-- Filters -->
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="relative w-full max-w-sm">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-muted-foreground">
                        <component :is="Icons.SearchIcon" class="w-4 h-4" />
                    </div>
                    <Input 
                        v-model="search" 
                        type="text" 
                        placeholder="Cari aktivitas..." 
                        class="pl-10"
                    />
                </div>
            </div>

            <!-- Table -->
            <div class="bg-card rounded-xl border border-border overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-muted-foreground uppercase bg-muted/50 border-b border-border">
                            <tr>
                                <th class="px-6 py-4 font-medium">Waktu</th>
                                <th class="px-6 py-4 font-medium">User</th>
                                <th class="px-6 py-4 font-medium">Aksi</th>
                                <th class="px-6 py-4 font-medium">Deskripsi</th>
                                <th class="px-6 py-4 font-medium">IP Address</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            <tr v-for="log in logs.data" :key="log.ulid" class="hover:bg-muted/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ new Date(log.created_at).toLocaleString('id-ID') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap font-medium">
                                    {{ log.causer?.name || 'Sistem / Guest' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="['px-2.5 py-0.5 rounded-full text-xs font-medium', getEventColor(log.event)]">
                                        {{ log.event.toUpperCase() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-muted-foreground">
                                    {{ log.description }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-muted-foreground">
                                    {{ log.ip_address || '-' }}
                                </td>
                            </tr>
                            <tr v-if="logs.data.length === 0">
                                <td colspan="5" class="px-6 py-8 text-center text-muted-foreground">
                                    Tidak ada data aktivitas yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div v-if="logs.total > 0" class="flex items-center justify-between px-6 py-4 border-t border-border bg-muted/20">
                    <span class="text-sm text-muted-foreground">
                        Menampilkan <span class="font-medium text-foreground">{{ logs.from }}</span> - 
                        <span class="font-medium text-foreground">{{ logs.to }}</span> dari 
                        <span class="font-medium text-foreground">{{ logs.total }}</span> data
                    </span>
                    <div class="flex items-center gap-1">
                        <template v-for="(link, index) in logs.links" :key="index">
                            <Link
                                v-if="link.url"
                                :href="link.url"
                                :class="[
                                    'px-3 py-1 text-sm rounded-md transition-colors',
                                    link.active 
                                        ? 'bg-primary text-primary-foreground font-medium' 
                                        : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                ]"
                                v-html="link.label"
                            />
                            <span 
                                v-else 
                                class="px-3 py-1 text-sm text-muted-foreground opacity-50 cursor-not-allowed rounded-md"
                                v-html="link.label"
                            />
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
