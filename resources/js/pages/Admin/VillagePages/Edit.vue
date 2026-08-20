<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { ArrowLeftIcon, ExternalLinkIcon } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { VillagePage } from '@/types/village-page';
import VillagePageForm from './Components/VillagePageForm.vue';

defineOptions({ layout: AdminLayout });

defineProps<{
    page: { data: VillagePage };
    statuses: string[];
    pageTypes: string[];
}>();
</script>

<template>
    <Head :title="`Edit Halaman: ${page.data.title}`" />

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
                    <h2 class="text-2xl font-bold tracking-tight">Edit Halaman Desa</h2>
                    <p class="text-muted-foreground">Perbarui informasi profil desa.</p>
                </div>
            </div>
            
            <Button variant="outline" as-child v-if="page.data.status === 'published'">
                <a :href="route('profile.show', page.data.slug)" target="_blank">
                    <ExternalLinkIcon class="w-4 h-4 mr-2" />
                    Lihat Publik
                </a>
            </Button>
        </div>

        <VillagePageForm
            :page="page.data"
            :statuses="statuses"
            :page-types="pageTypes"
        />
    </div>
</template>
