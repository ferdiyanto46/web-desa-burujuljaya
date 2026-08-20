<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import type { VillageService } from '@/types/village-service';
import VillageServiceForm from './Components/VillageServiceForm.vue';

defineProps<{
    villageService: { data: VillageService };
    statuses: Record<string, string>;
}>();
</script>

<template>
    <Head title="Edit Layanan Desa" />

    <DashboardContainer>
        <template #header>
            <PageTitle 
                title="Edit Layanan Desa" 
                :description="`Edit informasi layanan ${villageService.data.title}`"
            />
        </template>

        <div class="mt-6">
            <VillageServiceForm 
                :village-service="villageService.data" 
                :statuses="statuses" 
                is-edit 
            />
        </div>
    </DashboardContainer>
</template>
