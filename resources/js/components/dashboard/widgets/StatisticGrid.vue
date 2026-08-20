<script setup lang="ts">
import { Icons } from '@/constants/icons';
import StatisticCard from './StatisticCard.vue';

defineProps<{
    statistics: {
        total_articles?: number;
        total_announcements?: number;
        total_gallery_items?: number;
        total_tourism?: number;
        total_umkm?: number;
        total_products?: number;
        total_village_services?: number;
        total_faq?: number;
        total_contact_entries?: number;
        total_village_pages?: number;
        articles_published?: number;
        articles_draft?: number;
        announcements_published?: number;
        announcements_draft?: number;
    }
}>();
</script>

<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <StatisticCard 
            title="Artikel" 
            :value="statistics.total_articles ?? 0" 
            :icon="Icons.FileTextIcon" 
            colorClass="bg-blue-100 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" 
        />
        <StatisticCard 
            title="Pengumuman" 
            :value="statistics.total_announcements ?? 0" 
            :icon="Icons.MegaphoneIcon" 
            colorClass="bg-emerald-100 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400" 
        />
        <StatisticCard 
            title="Galeri" 
            :value="statistics.total_gallery_items ?? 0" 
            :icon="Icons.ImageIcon" 
            colorClass="bg-purple-100 text-purple-600 dark:bg-purple-900/30 dark:text-purple-400" 
        />
        <StatisticCard 
            title="UMKM" 
            :value="statistics.total_umkm ?? 0" 
            :icon="Icons.StoreIcon" 
            colorClass="bg-amber-100 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400" 
        />
        <StatisticCard 
            title="Produk" 
            :value="statistics.total_products ?? 0" 
            :icon="Icons.ShoppingBagIcon" 
            colorClass="bg-pink-100 text-pink-600 dark:bg-pink-900/30 dark:text-pink-400" 
        />
        <StatisticCard 
            title="Pesan Kontak" 
            :value="statistics.total_contact_entries ?? 0" 
            :icon="Icons.MessageSquareIcon" 
            colorClass="bg-cyan-100 text-cyan-600 dark:bg-cyan-900/30 dark:text-cyan-400" 
        />
    </div>
</template>
