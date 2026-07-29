<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';

const props = defineProps<{
    tiket: string;
    complaint: any;
}>();

const searchTiket = ref(props.tiket || '');

const submitSearch = () => {
    if (searchTiket.value.trim()) {
        router.get(route('complaints.track'), { tiket: searchTiket.value.trim() }, { preserveState: true });
    }
};

const formatDate = (dateString: string) => {
    return new Intl.DateTimeFormat('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    }).format(new Date(dateString));
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'pending': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'resolved': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800';
        case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'pending': return 'Menunggu Antrean';
        case 'processing': return 'Sedang Diproses';
        case 'resolved': return 'Selesai';
        case 'rejected': return 'Ditolak';
        default: return 'Tidak Diketahui';
    }
};

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'pending': return Icons.ClockIcon;
        case 'processing': return Icons.LoaderIcon;
        case 'resolved': return Icons.CheckCircleIcon;
        case 'rejected': return Icons.XIcon;
        default: return Icons.AlertCircleIcon;
    }
};
</script>

<template>
    <Head title="Lacak Pengaduan" />

    <PublicLayout>
        <template #banner>
            <PageBanner title="Lacak Pengaduan" subtitle="Pantau status penyelesaian laporan dan pengaduan Anda" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pengaduan', route: 'complaints.create' }, { name: 'Lacak' }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-4xl mx-auto">
            
            <!-- Success Message from redirection -->
            <div v-if="($page.props.flash as any)?.success" class="mb-8 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-2xl p-6 flex items-start gap-4">
                <component :is="Icons.CheckCircleIcon" class="w-6 h-6 text-green-600 dark:text-green-400 mt-0.5 flex-shrink-0" />
                <div>
                    <h3 class="font-bold text-green-800 dark:text-green-300 mb-1">Berhasil!</h3>
                    <p class="text-sm text-green-700 dark:text-green-400">{{ ($page.props.flash as any).success }}</p>
                </div>
            </div>

            <!-- Search Box -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12 mb-8 text-center">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <component :is="Icons.SearchIcon" class="w-10 h-10 text-blue-600 dark:text-blue-400" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Cek Status Laporan</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-lg mx-auto">Masukkan Nomor Tiket Pengaduan (ULID) yang Anda terima saat mengirim laporan untuk memantau perkembangannya.</p>
                
                <form @submit.prevent="submitSearch" class="max-w-xl mx-auto flex gap-3">
                    <input 
                        type="text" 
                        v-model="searchTiket" 
                        placeholder="Contoh: 01HABC..." 
                        class="flex-grow rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 px-6 font-mono tracking-widest text-center shadow-sm"
                        required
                    >
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Result -->
            <div v-if="tiket">
                <div v-if="complaint" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Header -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tiket: {{ complaint.ulid }}</p>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white">{{ complaint.title }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <span :class="['inline-flex items-center px-4 py-2 rounded-full text-sm font-bold border shadow-sm', getStatusColor(complaint.status)]">
                                <component :is="getStatusIcon(complaint.status)" class="w-4 h-4 mr-2" />
                                {{ getStatusLabel(complaint.status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-8 space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Detail Laporan</h4>
                                <ul class="space-y-4">
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Kategori</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ complaint.category?.name || '-' }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Lokasi Kejadian</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ complaint.location }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-1">Waktu Pelaporan</span>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ formatDate(complaint.created_at) }}</span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Deskripsi</h4>
                                <p class="text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line">{{ complaint.description }}</p>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div v-if="complaint.attachments && complaint.attachments.length > 0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Lampiran</h4>
                            <div class="flex gap-4 overflow-x-auto pb-4">
                                <a 
                                    v-for="(attachment, index) in complaint.attachments" 
                                    :key="index"
                                    :href="attachment.file_url"
                                    target="_blank"
                                    class="relative w-32 h-32 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden group flex-shrink-0"
                                >
                                    <img :src="attachment.file_url" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <component :is="Icons.EyeIcon" class="w-6 h-6 text-white" />
                                    </div>
                                </a>
                            </div>
                        </div>

                        <!-- Admin Response -->
                        <div v-if="complaint.response" class="bg-green-50 dark:bg-gray-700/50 rounded-2xl p-6 border border-green-100 dark:border-gray-600">
                            <div class="flex items-center gap-2 mb-4">
                                <component :is="Icons.MessageSquareIcon" class="w-5 h-5 text-green-600 dark:text-green-400" />
                                <h4 class="font-bold text-gray-900 dark:text-white">Tanggapan Petugas</h4>
                            </div>
                            <p class="text-gray-700 dark:text-gray-300 text-sm whitespace-pre-line">{{ complaint.response }}</p>
                            <p v-if="complaint.resolved_at" class="text-xs text-gray-500 mt-4 text-right">Ditanggapi pada: {{ formatDate(complaint.resolved_at) }}</p>
                        </div>
                    </div>
                </div>
                
                <div v-else class="bg-red-50 dark:bg-red-900/20 rounded-3xl border border-red-100 dark:border-red-900 p-8 text-center max-w-2xl mx-auto">
                    <component :is="Icons.AlertCircleIcon" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-400 mb-2">Laporan Tidak Ditemukan</h3>
                    <p class="text-red-600 dark:text-red-300 text-sm">Pastikan Nomor Tiket yang Anda masukkan sudah benar. Nomor Tiket biasanya berawalan '01H...' dan peka terhadap huruf besar/kecil.</p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>

