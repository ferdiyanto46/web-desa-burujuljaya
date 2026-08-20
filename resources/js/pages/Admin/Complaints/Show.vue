<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ArrowLeft, CheckCircle2, Clock, FileText, Image as ImageIcon, Send, ShieldAlert, User } from 'lucide-vue-next';
import DashboardContainer from '@/components/dashboard/DashboardContainer.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { route } from '@/lib/route';

interface Attachment {
    id: number;
    file_path: string;
    file_name: string;
    file_type: string;
    file_size: number;
}

interface HistoryItem {
    id: number;
    actor_type: string;
    status_from: string | null;
    status_to: string;
    notes: string | null;
    created_at: string;
    user?: {
        name: string;
    };
}

interface ComplaintDetail {
    id: number;
    ulid: string;
    tracking_code?: string;
    title: string;
    body: string;
    reporter_name: string;
    reporter_email?: string | null;
    reporter_phone?: string | null;
    is_anonymous: boolean;
    status: 'unread' | 'processing' | 'resolved' | 'rejected' | string;
    created_at: string;
    category?: {
        id: number;
        name: string;
    };
    attachments?: Attachment[];
    histories?: HistoryItem[];
}

interface Props {
    complaint: ComplaintDetail | { data: ComplaintDetail };
}

const props = defineProps<Props>();

const item = (props.complaint as any)?.data ?? props.complaint;

const form = useForm({
    status: item.status || 'unread',
    notes: '',
});

const submitStatus = () => {
    form.put(route('admin.complaints.update', item.id), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('notes');
        },
    });
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'unread': return 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400 border-amber-200 dark:border-amber-800';
        case 'processing': return 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400 border-blue-200 dark:border-blue-800';
        case 'resolved': return 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400 border-green-200 dark:border-green-800';
        case 'rejected': return 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 border-red-200 dark:border-red-800';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-800';
    }
};

const getStatusLabel = (status: string) => {
    switch (status) {
        case 'unread': return 'Belum Dibaca';
        case 'processing': return 'Sedang Diproses';
        case 'resolved': return 'Selesai';
        case 'rejected': return 'Ditolak';
        default: return status;
    }
};

const formatSize = (bytes: number) => {
    if (!bytes) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
};
</script>

<template>
    <Head :title="`Detail Pengaduan - ${item.title}`" />

    <DashboardContainer>
        <template #header>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <Link
                        :href="route('admin.complaints.index')"
                        class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-colors text-gray-500 dark:text-gray-400"
                    >
                        <ArrowLeft class="w-5 h-5" />
                    </Link>
                    <div>
                        <PageTitle 
                            :title="item.title" 
                            :description="`Kode Tiket: ${item.tracking_code || item.ulid}`"
                        />
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span :class="['inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold border', getStatusColor(item.status)]">
                        {{ getStatusLabel(item.status) }}
                    </span>
                </div>
            </div>
        </template>

        <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left 2 Cols: Complaint Info & Attachments -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Main Details Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <FileText class="w-5 h-5 text-blue-600" />
                        Rincian Laporan Pengaduan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Kategori Masalah</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ item.category?.name || '-' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Waktu Masuk</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                {{ item.created_at ? new Date(item.created_at).toLocaleString('id-ID', { dateStyle: 'long', timeStyle: 'short' }) : '-' }}
                            </span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1 font-medium">Judul Laporan</span>
                            <p class="text-base font-bold text-gray-900 dark:text-white">{{ item.title }}</p>
                        </div>

                        <div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-2 font-medium">Isi Deskripsi Laporan</span>
                            <div class="bg-gray-50 dark:bg-gray-900/40 p-4 rounded-xl text-sm text-gray-800 dark:text-gray-200 whitespace-pre-line leading-relaxed border border-gray-100 dark:border-gray-700">
                                {{ item.body }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reporter Info Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <User class="w-5 h-5 text-blue-600" />
                        Informasi Pelapor
                    </h3>

                    <div v-if="item.is_anonymous" class="flex items-center gap-3 p-4 bg-amber-50 dark:bg-amber-950/30 rounded-xl border border-amber-200 dark:border-amber-900/50">
                        <ShieldAlert class="w-6 h-6 text-amber-600 flex-shrink-0" />
                        <div>
                            <p class="text-sm font-bold text-amber-900 dark:text-amber-300">Pengaduan Anonim</p>
                            <p class="text-xs text-amber-700 dark:text-amber-400">Pelapor memilih untuk menyembunyikan identitas pribadinya.</p>
                        </div>
                    </div>

                    <div v-else class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Nama Lengkap</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ item.reporter_name || '-' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Nomor Telepon / WA</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ item.reporter_phone || '-' }}</span>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-xl">
                            <span class="text-xs text-gray-500 dark:text-gray-400 block mb-1">Email</span>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ item.reporter_email || '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Attachments Card -->
                <div v-if="item.attachments && item.attachments.length > 0" class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <ImageIcon class="w-5 h-5 text-blue-600" />
                        Lampiran Bukti ({{ item.attachments.length }})
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        <div 
                            v-for="att in item.attachments" 
                            :key="att.id"
                            class="group relative border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden bg-gray-50 dark:bg-gray-900/50"
                        >
                            <a :href="`/storage/${att.file_path}`" target="_blank" class="block p-3">
                                <div class="h-32 bg-gray-200 dark:bg-gray-800 rounded-lg overflow-hidden flex items-center justify-center mb-2">
                                    <img 
                                        v-if="att.file_type?.startsWith('image/')"
                                        :src="`/storage/${att.file_path}`" 
                                        :alt="att.file_name" 
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform"
                                    />
                                    <FileText v-else class="w-8 h-8 text-gray-400" />
                                </div>
                                <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ att.file_name }}</p>
                                <span class="text-[11px] text-gray-500">{{ formatSize(att.file_size) }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right 1 Col: Status Action & History Timeline -->
            <div class="space-y-6">
                <!-- Update Status Form -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <CheckCircle2 class="w-5 h-5 text-blue-600" />
                        Tindak Lanjut & Status
                    </h3>

                    <form @submit.prevent="submitStatus" class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Ubah Status Pengaduan</label>
                            <select 
                                v-model="form.status"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            >
                                <option value="unread">Belum Dibaca</option>
                                <option value="processing">Sedang Diproses</option>
                                <option value="resolved">Selesai / Teratasi</option>
                                <option value="rejected">Ditolak</option>
                            </select>
                            <div v-if="form.errors.status" class="mt-1 text-xs text-red-600">{{ form.errors.status }}</div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Catatan Tindak Lanjut</label>
                            <textarea 
                                v-model="form.notes"
                                rows="3"
                                placeholder="Tuliskan catatan progres atau respon untuk pelapor..."
                                class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:ring-blue-500 focus:border-blue-500 shadow-sm"
                            ></textarea>
                            <div v-if="form.errors.notes" class="mt-1 text-xs text-red-600">{{ form.errors.notes }}</div>
                        </div>

                        <button 
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex items-center justify-center gap-2 py-2.5 px-4 rounded-xl text-sm font-bold text-white bg-blue-600 hover:bg-blue-700 focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors shadow-sm disabled:opacity-50"
                        >
                            <Send class="w-4 h-4" />
                            <span v-if="form.processing">Menyimpan...</span>
                            <span v-else>Simpan Perubahan</span>
                        </button>
                    </form>
                </div>

                <!-- History Timeline Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 border-b border-gray-100 dark:border-gray-700 pb-3">
                        <Clock class="w-5 h-5 text-blue-600" />
                        Riwayat Penanganan
                    </h3>

                    <div v-if="item.histories && item.histories.length > 0" class="relative pl-6 space-y-6 before:absolute before:left-2 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-200 dark:before:bg-gray-700">
                        <div 
                            v-for="hist in item.histories" 
                            :key="hist.id"
                            class="relative"
                        >
                            <div class="absolute -left-[23px] top-1 w-3.5 h-3.5 rounded-full border-2 border-white dark:border-gray-800 bg-blue-600"></div>
                            
                            <div>
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">
                                        {{ getStatusLabel(hist.status_to) }}
                                    </span>
                                    <span class="text-[11px] text-gray-500 dark:text-gray-400">
                                        {{ hist.created_at ? new Date(hist.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }) : '-' }}
                                    </span>
                                </div>
                                <p v-if="hist.notes" class="mt-1 text-xs text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-900/50 p-2.5 rounded-lg border border-gray-100 dark:border-gray-700">
                                    {{ hist.notes }}
                                </p>
                                <p class="text-[11px] text-gray-400 mt-1">
                                    Oleh: <span class="font-medium text-gray-600 dark:text-gray-300">{{ hist.user?.name || (hist.actor_type === 'citizen' ? 'Pelapor' : 'Sistem') }}</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-else class="text-xs text-gray-500 text-center py-4">
                        Belum ada riwayat perubahan status.
                    </div>
                </div>
            </div>
        </div>
    </DashboardContainer>
</template>
