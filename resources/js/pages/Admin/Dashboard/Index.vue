<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import QuickActions from '@/components/dashboard/widgets/QuickActions.vue';
import RecentActivities from '@/components/dashboard/widgets/RecentActivities.vue';
import RecentArticles from '@/components/dashboard/widgets/RecentArticles.vue';
import RecentComplaints from '@/components/dashboard/widgets/RecentComplaints.vue';
import StatisticGrid from '@/components/dashboard/widgets/StatisticGrid.vue';
import SystemStatus from '@/components/dashboard/widgets/SystemStatus.vue';
import WelcomeCard from '@/components/dashboard/widgets/WelcomeCard.vue';
import AdminLayout from '@/layouts/AdminLayout.vue';

defineProps<{
    statistics: {
        articles: number;
        announcements: number;
        agendas: number;
        documents: number;
        complaints: number;
        products: number;
        umkms: number;
        tourisms: number;
    };
    recentArticles: Array<{
        id: number;
        title: string;
        status: string;
        published_at: string | null;
        user?: {
            name: string;
        };
    }>;
    recentComplaints: Array<{
        id: number;
        tracking_code: string;
        title: string;
        status: string;
        created_at: string;
    }>;
    recentActivities: Array<{
        id: number;
        description: string;
        created_at: string;
        user?: {
            name: string;
        };
    }>;
    systemStatus: {
        php_version: string;
        laravel_version: string;
        environment: string;
        database: string;
        storage: string;
    };
}>();
</script>

<template>
    <Head title="Dashboard" />

    <AdminLayout>
        <div class="space-y-6">
            <!-- Header Section -->
            <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                <PageTitle 
                    title="Dashboard" 
                    description="Ringkasan aktivitas dan metrik Sistem Informasi Desa."
                />
            </div>

            <!-- Welcome Card -->
            <WelcomeCard />

            <!-- Statistic Grid -->
            <StatisticGrid :statistics="statistics" />

            <!-- Main Widgets Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column (Span 2) -->
                <div class="lg:col-span-2 space-y-6">
                    <RecentArticles :articles="recentArticles" />
                    <RecentComplaints :complaints="recentComplaints" />
                </div>
                
                <!-- Right Column (Span 1) -->
                <div class="space-y-6">
                    <QuickActions />
                    <RecentActivities :activities="recentActivities" />
                    <SystemStatus :systemStatus="systemStatus" />
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
