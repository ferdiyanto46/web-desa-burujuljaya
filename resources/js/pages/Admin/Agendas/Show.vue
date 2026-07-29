<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ChevronRightIcon, CalendarIcon, MapPinIcon, ClockIcon, EditIcon, ArrowLeftIcon } from 'lucide-vue-next';
import AdminLayout from '@/layouts/AdminLayout.vue';
import type { Agenda } from '@/types';

defineOptions({ layout: AdminLayout });

defineProps<{
    agenda: { data: Agenda };
}>();

const getPublicationColor = (status: string) => {
    switch (status) {
        case 'published': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'draft': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400';
        case 'archived': return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};

const getEventStatusColor = (status: string) => {
    switch (status) {
        case 'upcoming': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400';
        case 'ongoing': return 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400';
        case 'finished': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400';
        case 'cancelled': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400';
    }
};
</script>

<template>
    <Head :title="`Detail Agenda: ${agenda.data.title}`" />
    
    <div class="mb-6 flex flex-col justify-between space-y-3 sm:flex-row sm:items-center sm:space-y-0">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Detail Agenda</h1>
            <nav class="mt-1 flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                    <li>
                        <a :href="route('admin.dashboard')" class="hover:text-gray-900 dark:hover:text-white">Dashboard</a>
                    </li>
                    <li>
                        <ChevronRightIcon class="h-4 w-4" />
                    </li>
                    <li>
                        <a :href="route('admin.agendas.index')" class="hover:text-gray-900 dark:hover:text-white">Agenda</a>
                    </li>
                    <li>
                        <ChevronRightIcon class="h-4 w-4" />
                    </li>
                    <li class="font-medium text-gray-900 dark:text-gray-200">Detail</li>
                </ol>
            </nav>
        </div>
        
        <div class="flex space-x-3">
            <button
                @click="router.visit(route('admin.agendas.index'))"
                class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                <ArrowLeftIcon class="-ml-1 mr-2 h-4 w-4" />
                Kembali
            </button>
            <button
                @click="router.visit(route('admin.agendas.edit', agenda.data.ulid))"
                class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                <EditIcon class="-ml-1 mr-2 h-4 w-4" />
                Edit Agenda
            </button>
        </div>
    </div>
    
    <div class="mx-auto max-w-7xl">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Main Content Area -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Banner Image -->
                <div v-if="agenda.data.featured_image_path" class="rounded-xl overflow-hidden shadow-sm border border-gray-200 dark:border-gray-700">
                    <img :src="`/storage/${agenda.data.featured_image_path}`" :alt="agenda.data.title" class="w-full h-auto object-cover max-h-96" />
                </div>
                
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <div class="flex items-center space-x-3 mb-4">
                        <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase', getEventStatusColor(agenda.data.event_status)]">
                            {{ agenda.data.event_status }}
                        </span>
                        <span :class="['inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold uppercase', getPublicationColor(agenda.data.status)]">
                            {{ agenda.data.status }}
                        </span>
                    </div>
                    
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">{{ agenda.data.title }}</h2>
                    
                    <div class="prose prose-blue max-w-none dark:prose-invert" v-html="agenda.data.content"></div>
                </div>
            </div>
            
            <!-- Sidebar Area -->
            <div class="space-y-6">
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Informasi Kegiatan</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <CalendarIcon class="h-5 w-5 text-gray-400 mr-3 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Tanggal Mulai</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ new Date(agenda.data.start_datetime).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    <ClockIcon class="h-3.5 w-3.5 inline mr-1" />
                                    {{ agenda.data.start_datetime.substring(11, 16) }} WIB
                                </p>
                            </div>
                        </div>
                        
                        <div v-if="agenda.data.end_datetime" class="flex items-start">
                            <CalendarIcon class="h-5 w-5 text-gray-400 mr-3 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Tanggal Selesai</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ new Date(agenda.data.end_datetime).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                                    <ClockIcon class="h-3.5 w-3.5 inline mr-1" />
                                    {{ agenda.data.end_datetime.substring(11, 16) }} WIB
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <MapPinIcon class="h-5 w-5 text-gray-400 mr-3 mt-0.5" />
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Lokasi</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ agenda.data.location }}</p>
                                <a 
                                    v-if="agenda.data.location_url" 
                                    :href="agenda.data.location_url" 
                                    target="_blank" 
                                    rel="noopener noreferrer"
                                    class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mt-1 inline-flex items-center"
                                >
                                    Buka di Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 uppercase tracking-wider text-gray-500">Meta Data</h3>
                    
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Dibuat oleh</dt>
                            <dd class="font-medium text-gray-900 dark:text-gray-100">{{ agenda.data.author?.name || 'Sistem' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Dibuat pada</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ agenda.data.created_at ? new Date(agenda.data.created_at).toLocaleDateString('id-ID') : '-' }}</dd>
                        </div>
                        <div v-if="agenda.data.published_at" class="flex justify-between">
                            <dt class="text-gray-500 dark:text-gray-400">Dipublikasi</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ new Date(agenda.data.published_at).toLocaleDateString('id-ID') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</template>
