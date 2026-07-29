<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import ShareButtons from '@/components/public/ShareButtons.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    agenda: any;
}>();

const formatFullTime = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    }).format(new Date(dateString));
};

const getStatus = (startDate: string, endDate: string) => {
    const now = new Date();
    const start = new Date(startDate);
    const end = new Date(endDate);
    
    if (now < start) {
        return { label: 'Akan Datang', class: 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300' };
    } else if (now >= start && now <= end) {
        return { label: 'Sedang Berlangsung', class: 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300' };
    } else {
        return { label: 'Selesai', class: 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300' };
    }
};


</script>

<template>
    <Head :title="agenda.title" />

    <PublicLayout>
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Agenda', route: 'public.agendas.index' }, { name: agenda.title }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-4xl mx-auto">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                
                <!-- Header -->
                <div class="bg-emerald-50 dark:bg-gray-900/50 px-8 md:px-12 py-10 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-3 mb-6">
                        <span :class="['inline-flex items-center px-3 py-1 rounded-full text-sm font-medium', getStatus(agenda.start_date, agenda.end_date).class]">
                            {{ getStatus(agenda.start_date, agenda.end_date).label }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 dark:text-white leading-tight mb-8">
                        {{ agenda.title }}
                    </h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center flex-shrink-0 text-emerald-600 dark:text-emerald-400">
                                <component :is="Icons.ClockIcon" class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Waktu Pelaksanaan</h4>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Mulai: {{ formatFullTime(agenda.start_date) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium mt-1">Selesai: {{ formatFullTime(agenda.end_date) }}</div>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400">
                                <component :is="Icons.MapIcon" class="w-5 h-5" />
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Lokasi</h4>
                                <div class="text-sm text-gray-600 dark:text-gray-400">{{ agenda.location }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-8 md:px-12 py-10">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Deskripsi Agenda</h3>
                    <div class="prose prose-lg dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 mb-12">
                        <div v-html="agenda.content"></div>
                    </div>

                    <!-- Author & Share -->
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 border-t border-gray-100 dark:border-gray-700 gap-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                <component :is="Icons.UserIcon" class="w-5 h-5" />
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ agenda.author?.name || 'Pemerintah Desa Burujuljaya' }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Penyelenggara</div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-2">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Bagikan:</span>
                            <ShareButtons :title="agenda.title" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
