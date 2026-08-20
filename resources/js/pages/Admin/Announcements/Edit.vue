<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon, ExternalLinkIcon } from 'lucide-vue-next';
import AnnouncementForm from '@/components/announcements/AnnouncementForm.vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
import type { Announcement } from '@/types/announcement';

interface Props {
    announcement: { data: Announcement };
}

defineProps<Props>();
</script>

<template>
    <Head title="Edit Pengumuman" />

    <DashboardContainer>
        <template #header>
            <div class="flex flex-col">
                <button
                    @click="router.visit(route('admin.announcements.index'))"
                    class="mb-4 inline-flex w-fit items-center text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"
                >
                    <ArrowLeftIcon class="mr-1 h-4 w-4" />
                    Kembali ke Daftar Pengumuman
                </button>
                <div class="flex items-center justify-between">
                    <PageTitle 
                        title="Edit Pengumuman" 
                        description="Perbarui informasi pengumuman yang sudah ada."
                    />
                    <a
                        v-if="announcement.data.status === 'published'"
                        :href="`/pengumuman/${announcement.data.slug}`"
                        target="_blank"
                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        <ExternalLinkIcon class="mr-1 h-4 w-4" />
                        Lihat Publikasi
                    </a>
                </div>
            </div>
        </template>

        <div class="mt-6">
            <AnnouncementForm 
                :announcement="announcement.data"
                :submit-route="route('admin.announcements.update', { announcement: announcement.data.id })"
                :cancel-route="route('admin.announcements.index')"
                :is-edit="true"
            />
        </div>
    </DashboardContainer>
</template>
