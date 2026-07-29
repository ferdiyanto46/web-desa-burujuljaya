<script setup lang="ts">
import { Icons } from '@/constants/icons';
import StatisticCard from './StatisticCard.vue';

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
    }
}>();

</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
        <StatisticCard 
            title="Artikel" 
            :value="statistics.articles" 
            :icon="Icons.FileTextIcon" 
            colorClass="bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" 
        />
        <StatisticCard 
            title="Pengumuman" 
            :value="statistics.announcements" 
            :icon="Icons.MegaphoneIcon" 
            colorClass="bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" 
        />
        <StatisticCard 
            title="Agenda" 
            :value="statistics.agendas" 
            :icon="Icons.CalendarIcon" 
            colorClass="bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400" 
        />
        <StatisticCard 
            title="Dokumen" 
            :value="statistics.documents" 
            :icon="Icons.FolderIcon" 
            colorClass="bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400" 
        />
        <StatisticCard 
            title="Pengaduan" 
            :value="statistics.complaints" 
            :icon="Icons.AlertCircleIcon" 
            colorClass="bg-red-100 text-red-600 dark:bg-red-900/30 dark:text-red-400" 
        />
        <StatisticCard 
            title="Produk" 
            :value="statistics.products" 
            :icon="Icons.ShoppingBagIcon" 
            colorClass="bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400" 
        />
        <StatisticCard 
            title="UMKM" 
            :value="statistics.umkms" 
            :icon="Icons.StoreIcon" 
            colorClass="bg-indigo-100 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400" 
        />
        <StatisticCard 
            title="Wisata" 
            :value="statistics.tourisms" 
            :icon="Icons.MapIcon" 
            colorClass="bg-teal-100 text-teal-600 dark:bg-teal-900/30 dark:text-teal-400" 
        />
    </div>
</template>
