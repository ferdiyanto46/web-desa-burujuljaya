<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import VillageServiceForm from './Components/VillageServiceForm.vue';

defineProps<{
    statuses: Record<string, string>;
}>();
</script>

<template>
    <Head title="Tambah Layanan Desa" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Tambah Layanan Desa" 
                description="Tambahkan informasi layanan administrasi baru."
            />
        </template>

        <div class="mt-6">
            <VillageServiceForm :statuses="statuses" />
        </div>
    </DashboardContainer>
</template>
