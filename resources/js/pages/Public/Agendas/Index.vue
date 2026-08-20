<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import EmptySection from '@/components/public/EmptySection.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';

defineProps<{
    agendas: {
        data: any[];
        links: any[];
        current_page: number;
        last_page: number;
        total: number;
    };
}>();

const formatMonth = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', { month: 'short' }).format(new Date(dateString));
};

const formatDay = (dateString: string) => {
    return new Date(dateString).getDate();
};

const formatFullTime = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
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
    <Head title="Agenda Desa" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Agenda Desa" subtitle="Jadwal kegiatan dan acara mendatang di Desa Burujuljaya" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Agenda' }]" />
        </template>
        
        <div class="py-8 max-w-5xl mx-auto">
            <div v-if="agendas.data.length > 0">
                <div class="space-y-6 mb-12">
                    <Link 
                        v-for="agenda in agendas.data" 
                        :key="agenda.id" 
                        :href="route('agendas.show', agenda.slug)" 
                        class="flex flex-col sm:flex-row bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow group"
                    >
                        <!-- Date Box -->
                        <div class="sm:w-48 bg-emerald-50 dark:bg-gray-900 flex flex-col items-center justify-center p-6 border-b sm:border-b-0 sm:border-r border-gray-100 dark:border-gray-700">
                            <span class="text-emerald-600 dark:text-emerald-500 font-bold uppercase tracking-widest text-sm mb-1">{{ formatMonth(agenda.start_date) }}</span>
                            <span class="text-5xl font-extrabold text-gray-900 dark:text-white">{{ formatDay(agenda.start_date) }}</span>
                            <span class="text-gray-500 dark:text-gray-400 text-sm mt-2 font-medium">{{ new Date(agenda.start_date).getFullYear() }}</span>
                        </div>
                        
                        <!-- Content -->
                        <div class="p-6 sm:p-8 flex-grow flex flex-col justify-center">
                            <div class="flex items-center gap-2 mb-3">
                                <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getStatus(agenda.start_date, agenda.end_date).class]">
                                    {{ getStatus(agenda.start_date, agenda.end_date).label }}
                                </span>
                            </div>
                            
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">
                                {{ agenda.title }}
                            </h3>
                            
                            <div class="space-y-2 mt-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <component :is="Icons.ClockIcon" class="w-4 h-4 mr-2 text-gray-400" />
                                    {{ formatFullTime(agenda.start_date) }} - {{ formatFullTime(agenda.end_date) }}
                                </div>
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                    <component :is="Icons.MapIcon" class="w-4 h-4 mr-2 text-gray-400" />
                                    {{ agenda.location }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Action Area -->
                        <div class="bg-gray-50 dark:bg-gray-800/50 p-6 flex items-center justify-center sm:w-24 border-t sm:border-t-0 sm:border-l border-gray-100 dark:border-gray-700">
                            <div class="w-10 h-10 rounded-full bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center text-gray-400 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-emerald-600 transition-colors">
                                <component :is="Icons.ChevronRightIcon" class="w-5 h-5" />
                            </div>
                        </div>
                    </Link>
                </div>

                <!-- Pagination -->
                <div v-if="agendas.links.length > 3" class="flex justify-center mt-8">
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <template v-for="(link, i) in agendas.links" :key="i">
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <Link 
                                v-if="link.url"
                                :href="link.url"
                                v-html="link.label"
                                :class="[
                                    link.active ? 'z-10 bg-emerald-50 dark:bg-emerald-900/30 border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700',
                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === agendas.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                            <!-- eslint-disable-next-line vue/no-v-text-v-html-on-component -->
                            <span 
                                v-else 
                                v-html="link.label" 
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-400 text-sm font-medium"
                                :class="[
                                    i === 0 ? 'rounded-l-md' : '',
                                    i === agendas.links.length - 1 ? 'rounded-r-md' : ''
                                ]"
                            />
                        </template>
                    </nav>
                </div>
            </div>
            
            <EmptySection 
                v-else 
                title="Tidak Ada Agenda" 
                message="Belum ada agenda atau kegiatan mendatang." 
                :icon="Icons.CalendarIcon" 
            />
        </div>
    </PublicLayout>
</template>
