<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { 
    AlertCircle, 
    ArrowDown, 
    ArrowUp, 
    Award, 
    Check, 
    CheckCircle2, 
    FileText, 
    GripVertical, 
    Home, 
    Image as ImageIcon, 
    Loader2, 
    Map, 
    Phone, 
    Plus, 
    Save, 
    Target, 
    Trash2, 
    Upload 
} from 'lucide-vue-next';
import { computed, ref } from 'vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface VillageMission {
    id?: number;
    content?: string;
    statement?: string;
    sort_order?: number;
}

interface VillageProfile {
    id?: number;
    village_name?: string;
    village_code?: string;
    district?: string;
    regency?: string;
    province?: string;
    postal_code?: string;
    address?: string;
    phone?: string;
    email?: string;
    website_url?: string;
    history?: string;
    vision?: string;
    area_hectares?: string | number;
    latitude?: string | number;
    longitude?: string | number;
    established_year?: number;
    logo_path?: string;
    missions?: VillageMission[];
}

const props = defineProps<{
    profile: VillageProfile | null;
    logoUrl?: string | null;
}>();

const page = usePage();

const getInitialMissions = (): { id?: number; content: string; sort_order: number }[] => {
    if (props.profile?.missions && props.profile.missions.length > 0) {
        return props.profile.missions.map((m, idx) => ({
            id: m.id,
            content: m.content || m.statement || '',
            sort_order: m.sort_order || (idx + 1) * 10,
        }));
    }
    return [
        { content: '', sort_order: 10 },
    ];
};

const form = useForm({
    _method: 'PUT',
    village_name: props.profile?.village_name || '',
    village_code: props.profile?.village_code || '',
    district: props.profile?.district || '',
    regency: props.profile?.regency || '',
    province: props.profile?.province || '',
    postal_code: props.profile?.postal_code || '',
    address: props.profile?.address || '',
    phone: props.profile?.phone || '',
    email: props.profile?.email || '',
    website_url: props.profile?.website_url || '',
    history: props.profile?.history || '',
    vision: props.profile?.vision || '',
    missions: getInitialMissions(),
    area_hectares: props.profile?.area_hectares || '',
    latitude: props.profile?.latitude || '',
    longitude: props.profile?.longitude || '',
    established_year: props.profile?.established_year || '',
    logo: null as File | null,
});

const fileInputRef = ref<HTMLInputElement | null>(null);
const logoPreview = ref<string | null>(props.logoUrl || null);
const selectedFileName = ref<string | null>(null);
const selectedFileSize = ref<string | null>(null);
const clientLogoError = ref<string | null>(null);
const isDragging = ref(false);
const saveSuccess = ref(false);

const openFilePicker = () => {
    clientLogoError.value = null;
    fileInputRef.value?.click();
};

const processFile = (file: File) => {
    clientLogoError.value = null;

    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/svg+xml', 'image/webp'];
    if (!validTypes.includes(file.type) && !file.name.match(/\.(png|jpe?g|svg|webp)$/i)) {
        clientLogoError.value = 'Format file tidak didukung. Harap pilih gambar PNG, JPG, SVG, atau WEBP.';
        return;
    }

    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        clientLogoError.value = `Ukuran file terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)} MB). Maksimal 5 MB.`;
        return;
    }

    form.logo = file;
    selectedFileName.value = file.name;
    selectedFileSize.value = `${(file.size / 1024).toFixed(1)} KB`;
    logoPreview.value = URL.createObjectURL(file);
};

const handleLogoChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        processFile(target.files[0]);
    }
};

const handleDrop = (event: DragEvent) => {
    isDragging.value = false;
    if (event.dataTransfer?.files && event.dataTransfer.files[0]) {
        processFile(event.dataTransfer.files[0]);
    }
};

const removeSelectedLogo = () => {
    form.logo = null;
    selectedFileName.value = null;
    selectedFileSize.value = null;
    clientLogoError.value = null;
    logoPreview.value = props.logoUrl || null;
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
};

// Mission item helpers
const addMission = () => {
    form.missions.push({
        content: '',
        sort_order: (form.missions.length + 1) * 10,
    });
};

const removeMission = (index: number) => {
    form.missions.splice(index, 1);
};

const moveMissionUp = (index: number) => {
    if (index > 0) {
        const item = form.missions.splice(index, 1)[0];
        form.missions.splice(index - 1, 0, item);
    }
};

const moveMissionDown = (index: number) => {
    if (index < form.missions.length - 1) {
        const item = form.missions.splice(index, 1)[0];
        form.missions.splice(index + 1, 0, item);
    }
};

const submit = () => {
    clientLogoError.value = null;
    saveSuccess.value = false;

    // Filter out empty mission items before sending
    form.missions = form.missions.filter(m => m.content && m.content.trim() !== '');

    form.post(route('admin.village-profile.update'), {
        preserveScroll: true,
        forceFormData: true,
        onSuccess: () => {
            saveSuccess.value = true;
            selectedFileName.value = null;
            selectedFileSize.value = null;
            if (form.missions.length === 0) {
                form.missions.push({ content: '', sort_order: 10 });
            }
            setTimeout(() => {
                saveSuccess.value = false;
            }, 5000);
        },
    });
};

const hasErrors = computed(() => Object.keys(form.errors).length > 0 || clientLogoError.value !== null);
</script>

<template>
    <AdminLayout>
        <Head title="Profil Desa" />

        <div class="space-y-6 max-w-6xl mx-auto pb-12">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <PageTitle 
                    title="Kelola Profil Desa" 
                    subtitle="Kelola identitas resmi, logo, kontak, wilayah, serta visi dan misi desa" 
                />
                
                <div class="flex items-center gap-2">
                    <Button 
                        type="button" 
                        variant="default"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-2"
                        :disabled="form.processing"
                        @click="submit"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <Save v-else class="w-4 h-4" />
                        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}</span>
                    </Button>
                </div>
            </div>

            <!-- Success Alert Banner -->
            <div 
                v-if="saveSuccess || page.props.flash?.success" 
                class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-800/60 flex items-center gap-3 text-emerald-800 dark:text-emerald-300 transition-all duration-300"
            >
                <CheckCircle2 class="w-5 h-5 text-emerald-600 flex-shrink-0" />
                <div>
                    <p class="text-sm font-semibold">Perubahan Berhasil Disimpan!</p>
                    <p class="text-xs text-emerald-700 dark:text-emerald-400">Profil desa, visi-misi, serta informasi wilayah telah diperbarui.</p>
                </div>
            </div>

            <!-- Validation Error Summary Banner -->
            <div 
                v-if="hasErrors" 
                class="p-4 rounded-xl bg-red-50 dark:bg-red-950/40 border border-red-200 dark:border-red-800/60 flex items-start gap-3 text-red-800 dark:text-red-300"
            >
                <AlertCircle class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" />
                <div class="text-xs space-y-1">
                    <p class="text-sm font-semibold">Mohon periksa kembali isian formulir:</p>
                    <p v-if="clientLogoError">{{ clientLogoError }}</p>
                    <ul class="list-disc list-inside space-y-0.5" v-if="Object.keys(form.errors).length > 0">
                        <li v-for="(err, key) in form.errors" :key="key">{{ err }}</li>
                    </ul>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- 1. Identitas Dasar & Logo -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <Home class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Identitas Utama Desa & Logo</h3>
                            <p class="text-xs text-gray-500">Nama resmi, logo lambang desa, dan kode wilayah</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <!-- Logo Upload Column -->
                        <div class="space-y-3 flex flex-col items-center justify-center p-5 border-2 border-dashed rounded-xl transition-all duration-200 text-center relative"
                            :class="[
                                isDragging 
                                    ? 'border-emerald-500 bg-emerald-50/50 dark:bg-emerald-950/20' 
                                    : 'border-gray-300 dark:border-gray-700 bg-gray-50/60 dark:bg-gray-800/40 hover:bg-gray-50 dark:hover:bg-gray-800/60'
                            ]"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            @drop.prevent="handleDrop"
                        >
                            <!-- Image Preview Frame -->
                            <div 
                                class="relative w-36 h-36 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm flex items-center justify-center cursor-pointer group"
                                @click="openFilePicker"
                            >
                                <img 
                                    v-if="logoPreview" 
                                    :src="logoPreview" 
                                    alt="Logo Desa" 
                                    class="w-full h-full object-contain p-2 transition-transform duration-200 group-hover:scale-105"
                                />
                                <div v-else class="flex flex-col items-center justify-center text-gray-400 p-2">
                                    <ImageIcon class="w-12 h-12 stroke-1 text-gray-300 dark:text-gray-600 mb-1" />
                                    <span class="text-xs font-medium text-gray-500">Belum ada logo</span>
                                </div>

                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">
                                    Klik ganti logo
                                </div>
                            </div>

                            <!-- File Details if selected -->
                            <div v-if="selectedFileName" class="text-center space-y-1">
                                <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400 truncate max-w-[200px]" :title="selectedFileName">
                                    {{ selectedFileName }}
                                </p>
                                <p class="text-[11px] text-gray-400">{{ selectedFileSize }}</p>
                            </div>

                            <!-- Buttons -->
                            <div class="flex flex-col gap-2 w-full max-w-[200px]">
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    size="sm"
                                    class="w-full text-xs font-medium flex items-center justify-center gap-1.5 border-emerald-600/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30"
                                    @click="openFilePicker"
                                >
                                    <Upload class="w-3.5 h-3.5" />
                                    <span>{{ logoPreview ? 'Ganti Logo' : 'Upload Logo' }}</span>
                                </Button>

                                <Button 
                                    v-if="form.logo"
                                    type="button" 
                                    variant="ghost" 
                                    size="sm"
                                    class="w-full text-[11px] text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/30 h-7"
                                    @click="removeSelectedLogo"
                                >
                                    Batalkan Pilihan
                                </Button>
                            </div>

                            <input 
                                ref="fileInputRef"
                                id="logo-input" 
                                type="file" 
                                accept="image/png,image/jpeg,image/jpg,image/svg+xml,image/webp"
                                class="hidden" 
                                @change="handleLogoChange"
                            />
                            
                            <p class="text-[11px] text-gray-400">PNG, JPG, SVG, WEBP maks 5MB</p>
                            <span v-if="clientLogoError || form.errors.logo" class="text-xs text-red-500 font-medium leading-tight">
                                {{ clientLogoError || form.errors.logo }}
                            </span>
                        </div>

                        <!-- Basic Fields Column -->
                        <div class="md:col-span-2 space-y-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="village_name" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Desa <span class="text-red-500">*</span></Label>
                                    <Input 
                                        id="village_name" 
                                        v-model="form.village_name" 
                                        placeholder="cth: Burujuljaya" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.village_name }"
                                    />
                                    <span v-if="form.errors.village_name" class="text-xs text-red-500">{{ form.errors.village_name }}</span>
                                </div>

                                <div class="space-y-1.5">
                                    <Label for="village_code" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kode Wilayah Desa</Label>
                                    <Input 
                                        id="village_code" 
                                        v-model="form.village_code" 
                                        placeholder="cth: 32.06.18.2001" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.village_code }"
                                    />
                                    <span v-if="form.errors.village_code" class="text-xs text-red-500">{{ form.errors.village_code }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="district" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kecamatan <span class="text-red-500">*</span></Label>
                                    <Input 
                                        id="district" 
                                        v-model="form.district" 
                                        placeholder="cth: Parungponteng" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.district }"
                                    />
                                    <span v-if="form.errors.district" class="text-xs text-red-500">{{ form.errors.district }}</span>
                                </div>

                                <div class="space-y-1.5">
                                    <Label for="regency" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kabupaten / Kota <span class="text-red-500">*</span></Label>
                                    <Input 
                                        id="regency" 
                                        v-model="form.regency" 
                                        placeholder="cth: Tasikmalaya" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.regency }"
                                    />
                                    <span v-if="form.errors.regency" class="text-xs text-red-500">{{ form.errors.regency }}</span>
                                </div>

                                <div class="space-y-1.5">
                                    <Label for="province" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Provinsi <span class="text-red-500">*</span></Label>
                                    <Input 
                                        id="province" 
                                        v-model="form.province" 
                                        placeholder="cth: Jawa Barat" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.province }"
                                    />
                                    <span v-if="form.errors.province" class="text-xs text-red-500">{{ form.errors.province }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="postal_code" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Kode Pos</Label>
                                    <Input 
                                        id="postal_code" 
                                        v-model="form.postal_code" 
                                        placeholder="cth: 46185" 
                                        class="h-10"
                                    />
                                </div>

                                <div class="space-y-1.5">
                                    <Label for="established_year" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Tahun Berdiri</Label>
                                    <Input 
                                        id="established_year" 
                                        type="number"
                                        v-model="form.established_year" 
                                        placeholder="cth: 1982" 
                                        class="h-10"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Kontak & Alamat Kantor -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <Phone class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Kontak & Alamat Kantor Desa</h3>
                            <p class="text-xs text-gray-500">Informasi alamat fisik dan saluran komunikasi resmi desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1.5">
                            <Label for="address" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Alamat Lengkap Kantor Desa <span class="text-red-500">*</span></Label>
                            <Textarea 
                                id="address" 
                                v-model="form.address" 
                                rows="2" 
                                placeholder="cth: Jl. Raya Burujuljaya No. 01, Desa Burujuljaya..." 
                                class="resize-none"
                                :class="{ 'border-red-500': form.errors.address }"
                            />
                            <span v-if="form.errors.address" class="text-xs text-red-500">{{ form.errors.address }}</span>
                        </div>

                        <div class="space-y-1.5">
                            <Label for="phone" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nomor Telepon / WhatsApp</Label>
                            <Input 
                                id="phone" 
                                v-model="form.phone" 
                                placeholder="cth: 081234567890 / (0265) 123456" 
                                class="h-10"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <Label for="email" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Email Resmi Desa</Label>
                            <Input 
                                id="email" 
                                type="email"
                                v-model="form.email" 
                                placeholder="cth: kantor@burujuljaya.desa.id" 
                                class="h-10"
                                :class="{ 'border-red-500': form.errors.email }"
                            />
                            <span v-if="form.errors.email" class="text-xs text-red-500">{{ form.errors.email }}</span>
                        </div>

                        <div class="md:col-span-2 space-y-1.5">
                            <Label for="website_url" class="text-xs font-semibold text-gray-700 dark:text-gray-300">URL Website Resmi</Label>
                            <Input 
                                id="website_url" 
                                type="text"
                                v-model="form.website_url" 
                                placeholder="cth: https://burujuljaya.desa.id" 
                                class="h-10"
                                :class="{ 'border-red-500': form.errors.website_url }"
                            />
                            <span v-if="form.errors.website_url" class="text-xs text-red-500">{{ form.errors.website_url }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3. Data Geografis & Wilayah -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <Map class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Data Geografis & Wilayah</h3>
                            <p class="text-xs text-gray-500">Luas wilayah dan titik koordinat peta lokasi kantor desa</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="space-y-1.5">
                            <Label for="area_hectares" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Luas Wilayah (Hektar)</Label>
                            <Input 
                                id="area_hectares" 
                                type="number"
                                step="0.01"
                                v-model="form.area_hectares" 
                                placeholder="cth: 350.25" 
                                class="h-10"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <Label for="latitude" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Latitude (Garis Lintang)</Label>
                            <Input 
                                id="latitude" 
                                v-model="form.latitude" 
                                placeholder="cth: -7.5432100" 
                                class="h-10"
                            />
                        </div>

                        <div class="space-y-1.5">
                            <Label for="longitude" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Longitude (Garis Bujur)</Label>
                            <Input 
                                id="longitude" 
                                v-model="form.longitude" 
                                placeholder="cth: 108.1234567" 
                                class="h-10"
                            />
                        </div>
                    </div>
                </div>

                <!-- 4. Visi, Misi & Sejarah Desa -->
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                    <div class="flex items-center gap-3 pb-4 border-b border-gray-100 dark:border-gray-800">
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                            <Target class="w-5 h-5" />
                        </div>
                        <div>
                            <h3 class="text-base font-semibold text-gray-900 dark:text-gray-100">Visi, Misi & Sejarah Desa</h3>
                            <p class="text-xs text-gray-500">Visi pembangunan, butir-butir misi RPJMDes, dan sejarah asal-usul desa</p>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <!-- Visi Desa -->
                        <div class="space-y-1.5">
                            <div class="flex items-center gap-2">
                                <Target class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                                <Label for="vision" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Visi Pembangunan Desa</Label>
                            </div>
                            <Textarea 
                                id="vision" 
                                v-model="form.vision" 
                                rows="3" 
                                placeholder="Tuliskan visi utama pembangunan dan pelayanan desa..." 
                                class="resize-none"
                            />
                        </div>

                        <!-- Misi Desa Management -->
                        <div class="space-y-4 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <Award class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                                            Misi Desa ({{ form.missions.length }} Butir)
                                        </h4>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-0.5">
                                        Tambahkan butir-butir pernyataan misi desa yang akan ditampilkan pada profil publik.
                                    </p>
                                </div>
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    size="sm"
                                    class="border-emerald-600/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30 flex items-center gap-1.5 text-xs self-start sm:self-auto"
                                    @click="addMission"
                                >
                                    <Plus class="w-3.5 h-3.5" />
                                    <span>Tambah Butir Misi</span>
                                </Button>
                            </div>

                            <!-- List of Missions -->
                            <div v-if="form.missions.length > 0" class="space-y-3">
                                <div 
                                    v-for="(mission, index) in form.missions" 
                                    :key="index"
                                    class="flex items-start gap-3 p-3.5 rounded-xl border border-gray-200 dark:border-gray-800 bg-gray-50/70 dark:bg-gray-800/40 group hover:border-emerald-200 dark:hover:border-emerald-800/60 transition-colors"
                                >
                                    <!-- Number Badge -->
                                    <div class="w-7 h-7 rounded-lg bg-emerald-100 dark:bg-emerald-900/60 text-emerald-800 dark:text-emerald-300 font-bold text-xs flex items-center justify-center flex-shrink-0 mt-1">
                                        {{ index + 1 }}
                                    </div>

                                    <!-- Mission Content Input -->
                                    <div class="flex-1 space-y-1">
                                        <Textarea 
                                            v-model="mission.content" 
                                            rows="2"
                                            placeholder="Tuliskan pernyataan misi desa..."
                                            class="w-full bg-white dark:bg-gray-900 text-sm resize-none"
                                        />
                                    </div>

                                    <!-- Action Buttons -->
                                    <div class="flex flex-col sm:flex-row items-center gap-1 flex-shrink-0 mt-1">
                                        <Button 
                                            type="button" 
                                            variant="ghost" 
                                            size="sm"
                                            class="h-7 w-7 p-0 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                                            :disabled="index === 0"
                                            title="Pindahkan ke atas"
                                            @click="moveMissionUp(index)"
                                        >
                                            <ArrowUp class="w-3.5 h-3.5" />
                                        </Button>

                                        <Button 
                                            type="button" 
                                            variant="ghost" 
                                            size="sm"
                                            class="h-7 w-7 p-0 text-gray-400 hover:text-gray-700 dark:hover:text-gray-200"
                                            :disabled="index === form.missions.length - 1"
                                            title="Pindahkan ke bawah"
                                            @click="moveMissionDown(index)"
                                        >
                                            <ArrowDown class="w-3.5 h-3.5" />
                                        </Button>

                                        <Button 
                                            type="button" 
                                            variant="ghost" 
                                            size="sm"
                                            class="h-7 w-7 p-0 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/40"
                                            title="Hapus butir misi ini"
                                            @click="removeMission(index)"
                                        >
                                            <Trash2 class="w-3.5 h-3.5" />
                                        </Button>
                                    </div>
                                </div>
                            </div>

                            <div v-else class="p-6 text-center border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl bg-gray-50 dark:bg-gray-800/20">
                                <p class="text-xs text-gray-400 mb-2">Belum ada butir misi yang ditambahkan.</p>
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    size="sm"
                                    class="text-xs text-emerald-600"
                                    @click="addMission"
                                >
                                    <Plus class="w-3.5 h-3.5 mr-1" />
                                    Tambah Misi Pertama
                                </Button>
                            </div>
                        </div>

                        <!-- Sejarah Singkat Desa -->
                        <div class="space-y-1.5 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-2">
                                <FileText class="w-4 h-4 text-emerald-600 dark:text-emerald-400" />
                                <Label for="history" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Sejarah Singkat Desa</Label>
                            </div>
                            <Textarea 
                                id="history" 
                                v-model="form.history" 
                                rows="5" 
                                placeholder="Tuliskan sejarah asal-usul atau perkembangan desa dari masa ke masa..." 
                                class="resize-none"
                            />
                        </div>
                    </div>
                </div>

                <!-- Bottom Save Button -->
                <div class="flex justify-end gap-3 pt-4">
                    <Button 
                        type="submit" 
                        variant="default"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm px-6 flex items-center gap-2"
                        :disabled="form.processing"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <Save v-else class="w-4 h-4" />
                        <span>{{ form.processing ? 'Menyimpan Perubahan...' : 'Simpan Perubahan' }}</span>
                    </Button>
                </div>
            </form>
        </div>
    </AdminLayout>
</template>
