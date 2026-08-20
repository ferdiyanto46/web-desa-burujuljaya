<script setup lang="ts">
import { computed } from 'vue';
import { Head, useForm, Link } from '@inertiajs/vue3';

import Breadcrumb from '@/components/public/Breadcrumb.vue';
import PageBanner from '@/components/public/PageBanner.vue';
import { Icons } from '@/constants/icons';
import PublicLayout from '@/layouts/PublicLayout.vue';
import { route } from '@/lib/route';

interface Props {
    categories?: Array<{ id: number; name: string }> | { data: Array<{ id: number; name: string }> };
}

const props = defineProps<Props>();

const categoryList = computed(() => {
    const raw = props.categories;
    return Array.isArray(raw) ? raw : (raw?.data ?? []);
});

const form = useForm({
    title: '',
    body: '',
    category_id: '',
    is_anonymous: false,
    reporter_name: '',
    reporter_phone: '',
    reporter_email: '',
    location: '',
    attachments: [] as File[],
});



const handleFileChange = (e: Event) => {
    const target = e.target as HTMLInputElement;

    if (target.files) {
        const newFiles = Array.from(target.files);

        if (form.attachments.length + newFiles.length > 3) {
            alert('Maksimal lampiran adalah 3 file.');
            target.value = '';
            return;
        }

        // Validate max 10MB per file
        for (const file of newFiles) {
            if (file.size > 10 * 1024 * 1024) {
                alert(`File "${file.name}" melebihi ukuran maksimal 10 MB.`);
                target.value = '';
                return;
            }
        }

        form.attachments = [...form.attachments, ...newFiles];
        target.value = '';
    }
};

const removeFile = (index: number) => {
    form.attachments.splice(index, 1);
};

const submit = () => {
    form.post(route('complaints.store'), {
        preserveScroll: true,
        onSuccess: () => {
            // Success handled by redirect in controller
        },
    });
};
</script>

<template>
    <Head title="Buat Pengaduan" />

    <PublicLayout>
        <template #banner>
            <PageBanner pageKey="complaints" title="Layanan Pengaduan" subtitle="Sampaikan laporan, kritik, dan saran Anda untuk membangun Desa Burujuljaya yang lebih baik" />
        </template>
        
        <template #breadcrumb>
            <Breadcrumb :items="[{ name: 'Pengaduan' }]" />
        </template>
        
        <div class="py-8 lg:py-12 max-w-4xl mx-auto">
            
            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-3xl p-6 md:p-8 mb-8 border border-blue-100 dark:border-blue-800 flex flex-col md:flex-row gap-6 items-center">
                <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-800 flex items-center justify-center flex-shrink-0 text-blue-600 dark:text-blue-400">
                    <component :is="Icons.ShieldIcon" class="w-8 h-8" />
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Layanan Pengaduan Masyarakat</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300">Formulir ini ditujukan untuk melaporkan masalah infrastruktur, pelayanan publik, keamanan, atau hal lainnya di lingkungan Desa Burujuljaya. Anda dapat memilih untuk melaporkan secara anonim.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <form @submit.prevent="submit" class="p-8 md:p-12 space-y-8">
                    <!-- Error Summary Banner -->
                    <div v-if="Object.keys(form.errors).length > 0" class="bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800 rounded-2xl p-4 flex items-start gap-3">
                        <component :is="Icons.AlertCircleIcon" class="w-5 h-5 text-red-600 dark:text-red-400 mt-0.5 flex-shrink-0" />
                        <div>
                            <h4 class="text-sm font-bold text-red-800 dark:text-red-300">Mohon periksa kembali isian formulir:</h4>
                            <ul class="mt-1 list-disc list-inside text-xs text-red-700 dark:text-red-400 space-y-0.5">
                                <li v-for="(err, key) in form.errors" :key="key">{{ err }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Section: Informasi Pelapor -->
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-6">Informasi Pelapor</h4>
                        
                        <div class="mb-6 flex items-center">
                            <input 
                                id="is_anonymous" 
                                type="checkbox" 
                                v-model="form.is_anonymous"
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                            >
                            <label for="is_anonymous" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                                Sembunyikan identitas saya (Anonim)
                            </label>
                        </div>
                        
                        <div v-if="!form.is_anonymous" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="reporter_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input id="reporter_name" type="text" v-model="form.reporter_name" :required="!form.is_anonymous" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Masukkan nama Anda">
                                <div v-if="form.errors.reporter_name" class="mt-1 text-sm text-red-600">{{ form.errors.reporter_name }}</div>
                            </div>
                            <div>
                                <label for="reporter_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nomor Telepon/WA <span class="text-red-500">*</span></label>
                                <input id="reporter_phone" type="text" v-model="form.reporter_phone" :required="!form.is_anonymous" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Contoh: 08123456789">
                                <div v-if="form.errors.reporter_phone" class="mt-1 text-sm text-red-600">{{ form.errors.reporter_phone }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="reporter_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email (Opsional)</label>
                                <input id="reporter_email" type="email" v-model="form.reporter_email" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Alamat email untuk notifikasi">
                                <div v-if="form.errors.reporter_email" class="mt-1 text-sm text-red-600">{{ form.errors.reporter_email }}</div>
                            </div>
                        </div>
                        <div v-else class="bg-gray-50 dark:bg-gray-700/50 p-4 rounded-xl text-sm text-gray-500 dark:text-gray-400 italic">
                            Identitas Anda akan dirahasiakan dan tidak akan ditampilkan secara publik maupun kepada petugas lapangan secara langsung.
                        </div>
                    </div>
                    
                    <!-- Section: Rincian Pengaduan -->
                    <div>
                        <h4 class="text-xl font-bold text-gray-900 dark:text-white border-b border-gray-100 dark:border-gray-700 pb-4 mb-6">Rincian Laporan</h4>
                        
                        <div class="space-y-6">
                            <div>
                                <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Judul Laporan <span class="text-red-500">*</span></label>
                                <input id="title" type="text" v-model="form.title" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Ringkasan masalah yang dilaporkan">
                                <div v-if="form.errors.title" class="mt-1 text-sm text-red-600">{{ form.errors.title }}</div>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Kategori Masalah <span class="text-red-500">*</span></label>
                                    <select id="category_id" v-model="form.category_id" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                                        <option value="" disabled>Pilih kategori</option>
                                        <option v-for="cat in categoryList" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                                    </select>
                                    <div v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</div>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lokasi Kejadian <span class="text-red-500">*</span></label>
                                    <input id="location" type="text" v-model="form.location" required class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Nama jalan, RT/RW, atau patokan lokasi">
                                    <div v-if="form.errors.location" class="mt-1 text-sm text-red-600">{{ form.errors.location }}</div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="body" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Deskripsi Detail <span class="text-red-500">*</span></label>
                                <textarea id="body" v-model="form.body" required rows="5" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Ceritakan secara detail kronologi atau kondisi yang dilaporkan..."></textarea>
                                <div v-if="form.errors.body" class="mt-1 text-sm text-red-600">{{ form.errors.body }}</div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Lampiran Bukti (Opsional, Maks. 3 Foto)</label>
                                
                                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-xl hover:border-blue-500 transition-colors bg-gray-50 dark:bg-gray-750">
                                    <div class="space-y-1 text-center">
                                        <component :is="Icons.ImageIcon" class="mx-auto h-12 w-12 text-gray-400" />
                                        <div class="flex text-sm text-gray-600 dark:text-gray-400 justify-center">
                                            <label for="file-upload" class="relative cursor-pointer bg-transparent rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                                <span>Pilih file</span>
                                                <input id="file-upload" type="file" class="sr-only" multiple accept="image/jpeg,image/png,image/jpg,application/pdf,.doc,.docx" @change="handleFileChange" :disabled="form.attachments.length >= 3">
                                            </label>
                                            <p class="pl-1">atau tarik dan lepas di sini</p>
                                        </div>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, PDF, DOC hingga 10MB per file (maks. 3 file)</p>
                                    </div>
                                </div>
                                
                                <!-- File Preview -->
                                <ul v-if="form.attachments.length > 0" class="mt-4 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <li v-for="(file, index) in form.attachments" :key="index" class="relative rounded-lg overflow-hidden border border-gray-200 dark:border-gray-700 flex items-center justify-between p-2 bg-white dark:bg-gray-800">
                                        <span class="text-xs truncate max-w-[80%] text-gray-700 dark:text-gray-300">{{ file.name }}</span>
                                        <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700 bg-red-50 dark:bg-red-900/30 rounded p-1">
                                            <component :is="Icons.XIcon" class="w-4 h-4" />
                                        </button>
                                    </li>
                                </ul>
                                <div v-if="form.errors.attachments" class="mt-1 text-sm text-red-600">{{ form.errors.attachments }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="pt-6 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
                        <Link :href="route('complaints.track')" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">
                            Lacak Pengaduan
                        </Link>
                        
                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                        >
                            <span v-if="form.processing">Mengirim...</span>
                            <span v-else>Kirim Laporan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </PublicLayout>
</template>


