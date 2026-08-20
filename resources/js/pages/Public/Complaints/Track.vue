<script setup lang="ts">
import { Head, router, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';

const props = defineProps<{
    tiket?: string;
    complaint?: any;
}>();

const complaintDetail = computed(() => {
    if (!props.complaint) return null;
    return props.complaint.data ?? props.complaint;
});

const searchTiket = ref(props.tiket || '');

const submitSearch = () => {
    if (searchTiket.value.trim()) {
        router.get(route('complaints.track'), { tiket: searchTiket.value.trim() }, { preserveState: false });
    }
};

const formatDate = (dateString?: string) => {
    if (!dateString) return '-';
    try {
        const d = new Date(dateString);
        if (isNaN(d.getTime())) return dateString;
        return new Intl.DateTimeFormat('id-ID', {
            day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
        }).format(d);
    } catch {
        return dateString;
    }
};

const getStatusColor = (status?: string) => {
    switch (status) {
        case 'unread': return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'resolved': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800';
        case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-300 border-gray-200 dark:border-gray-700';
    }
};

const getStatusLabel = (status?: string) => {
    switch (status) {
        case 'unread': return 'Menunggu Tindak Lanjut';
        case 'processing': return 'Sedang Diproses';
        case 'resolved': return 'Selesai / Teratasi';
        case 'rejected': return 'Ditolak';
        default: return 'Belum Diproses';
    }
};

const getStatusIcon = (status?: string) => {
    switch (status) {
        case 'unread': return Icons.ClockIcon;
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
            <PageBanner title="Lacak Pengaduan" subtitle="Pantau status penyelesaian laporan dan pengaduan Anda secara transparan" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pengaduan', route: 'complaints.create' }, { name: 'Lacak' }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-4xl mx-auto px-4 sm:px-6">
            
            <!-- Success Message from redirection -->
            <div v-if="($page.props.flash as any)?.success" class="mb-8 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800 rounded-2xl p-6 flex items-start gap-4 shadow-sm">
                <component :is="Icons.CheckCircleIcon" class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mt-0.5 flex-shrink-0" />
                <div>
                    <h3 class="font-bold text-emerald-800 dark:text-emerald-300 mb-1">Pengaduan Berhasil Dikirim!</h3>
                    <p class="text-sm text-emerald-700 dark:text-emerald-400">{{ ($page.props.flash as any).success }}</p>
                </div>
            </div>

            <!-- Search Box -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12 mb-8 text-center">
                <div class="w-20 h-20 bg-blue-50 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-6">
                    <component :is="Icons.SearchIcon" class="w-10 h-10 text-blue-600 dark:text-blue-400" />
                </div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Cek Status Laporan</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-8 max-w-lg mx-auto">Masukkan Nomor Tiket Pengaduan (ULID) yang Anda terima saat mengirim laporan untuk memantau perkembangannya.</p>
                
                <form @submit.prevent="submitSearch" class="max-w-xl mx-auto flex flex-col sm:flex-row gap-3">
                    <input 
                        type="text" 
                        v-model="searchTiket" 
                        placeholder="Contoh: 01M02..." 
                        class="flex-grow rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 px-6 py-3 font-mono tracking-widest text-center shadow-sm"
                        required
                    >
                    <button type="submit" class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cari
                    </button>
                </form>
            </div>

            <!-- Result -->
            <div v-if="tiket">
                <div v-if="complaintDetail" class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                    
                    <!-- Header -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 md:p-8 border-b border-gray-100 dark:border-gray-700 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <p class="text-xs font-mono font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Tiket: {{ complaintDetail.ulid }}</p>
                            <h3 class="text-xl md:text-2xl font-bold text-gray-900 dark:text-white">{{ complaintDetail.title }}</h3>
                        </div>
                        <div class="flex-shrink-0">
                            <span :class="['inline-flex items-center px-4 py-2 rounded-full text-xs md:text-sm font-bold border shadow-sm', getStatusColor(complaintDetail.status)]">
                                <component :is="getStatusIcon(complaintDetail.status)" class="w-4 h-4 mr-2" />
                                {{ getStatusLabel(complaintDetail.status) }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="p-6 md:p-8 space-y-8">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Informasi Laporan</h4>
                                <ul class="space-y-3">
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-0.5">Kategori Masalah</span>
                                        <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ complaintDetail.category?.name || '-' }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-0.5">Waktu Pelaporan</span>
                                        <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ formatDate(complaintDetail.created_at) }}</span>
                                    </li>
                                    <li>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400 mb-0.5">Pelapor</span>
                                        <span class="font-semibold text-gray-900 dark:text-white text-sm">
                                            {{ complaintDetail.is_anonymous ? 'Anonim (Identitas Disembunyikan)' : (complaintDetail.reporter_name || 'Warga') }}
                                        </span>
                                    </li>
                                </ul>
                            </div>
                            
                            <div>
                                <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Isi Deskripsi Laporan</h4>
                                <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl text-gray-700 dark:text-gray-300 text-sm leading-relaxed whitespace-pre-line border border-gray-100 dark:border-gray-700">
                                    {{ complaintDetail.body || complaintDetail.description || '-' }}
                                </div>
                            </div>
                        </div>

                        <!-- Attachments -->
                        <div v-if="complaintDetail.attachments && complaintDetail.attachments.length > 0">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-4 border-b border-gray-100 dark:border-gray-700 pb-2">Lampiran Bukti ({{ complaintDetail.attachments.length }})</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                <a 
                                    v-for="(attachment, index) in complaintDetail.attachments" 
                                    :key="index"
                                    :href="attachment.file_url || `/storage/${attachment.file_path}`"
                                    target="_blank"
                                    class="relative rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden group p-2 bg-gray-50 dark:bg-gray-900/50 hover:border-blue-500 transition-colors"
                                >
                                    <div class="h-28 rounded-lg overflow-hidden flex items-center justify-center bg-gray-200 dark:bg-gray-800 mb-2">
                                        <img 
                                            v-if="attachment.file_type?.startsWith('image/') || attachment.file_url" 
                                            :src="attachment.file_url || `/storage/${attachment.file_path}`" 
                                            :alt="attachment.file_name" 
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform" 
                                        />
                                        <component :is="Icons.FileTextIcon" v-else class="w-8 h-8 text-gray-400" />
                                    </div>
                                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ attachment.file_name || `Lampiran ${index + 1}` }}</p>
                                </a>
                            </div>
                        </div>

                        <!-- Track Another / Back -->
                        <div class="pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <Link :href="route('complaints.create')" class="text-sm font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                ← Buat Pengaduan Lainnya
                            </Link>
                        </div>
                    </div>
                </div>
                
                <div v-else class="bg-red-50 dark:bg-red-900/20 rounded-3xl border border-red-100 dark:border-red-900 p-8 text-center max-w-2xl mx-auto">
                    <component :is="Icons.AlertCircleIcon" class="w-12 h-12 text-red-500 mx-auto mb-4" />
                    <h3 class="text-lg font-bold text-red-800 dark:text-red-400 mb-2">Laporan Tidak Ditemukan</h3>
                    <p class="text-red-600 dark:text-red-300 text-sm">Pastikan Nomor Tiket yang Anda masukkan sudah benar. Nomor Tiket peka terhadap huruf besar/kecil.</p>
                </div>
            </div>
        </div>
    </PublicLayout>
</template>
