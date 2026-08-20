<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ArrowLeftIcon } from 'lucide-vue-next';
import AnnouncementForm from '@/components/announcements/AnnouncementForm.vue';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';
</script>

<template>
    <Head title="Tambah Pengumuman" />

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
                <PageTitle 
                    title="Tambah Pengumuman Baru" 
                    description="Buat pengumuman atau informasi mendesak untuk warga desa."
                />
            </div>
        </template>

        <div class="mt-6">
            <AnnouncementForm 
                :submit-route="route('admin.announcements.store')"
                :cancel-route="route('admin.announcements.index')"
                :is-edit="false"
            />
        </div>
    </DashboardContainer>
</template>
