<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Layers, 
    Loader2, 
    Plus, 
    Save, 
    Upload, 
    User 
} from 'lucide-vue-next';
import { ref } from 'vue';
import ManagePositionsModal from '@/components/admin/ManagePositionsModal.vue';
import PageTitle from '@/components/dashboard/PageTitle.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AdminLayout from '@/layouts/AdminLayout.vue';

interface ApparatusPosition {
    id: number;
    name: string;
    order: number;
}

const props = defineProps<{
    positions: ApparatusPosition[];
}>();

const localPositions = ref<ApparatusPosition[]>([...props.positions]);

const form = useForm({
    name: '',
    position_id: props.positions.length > 0 ? props.positions[0].id : '',
    nip: '',
    education: '',
    photo: null as File | null,
    is_active: true,
});

const onPositionsChanged = (newPositions: ApparatusPosition[]) => {
    localPositions.value = [...newPositions];
};

const onPositionCreated = (newPos: ApparatusPosition) => {
    form.position_id = newPos.id;
};

const fileInputRef = ref<HTMLInputElement | null>(null);
const photoPreview = ref<string | null>(null);
const selectedFileName = ref<string | null>(null);
const selectedFileSize = ref<string | null>(null);
const clientPhotoError = ref<string | null>(null);
const isDragging = ref(false);
const positionsModalOpen = ref(false);

const openFilePicker = () => {
    clientPhotoError.value = null;
    fileInputRef.value?.click();
};

const processFile = (file: File) => {
    clientPhotoError.value = null;

    const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
    if (!validTypes.includes(file.type) && !file.name.match(/\.(png|jpe?g|webp)$/i)) {
        clientPhotoError.value = 'Format foto tidak didukung. Harap pilih gambar PNG, JPG, JPEG, atau WEBP.';
        return;
    }

    const maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
        clientPhotoError.value = `Ukuran file terlalu besar (${(file.size / (1024 * 1024)).toFixed(2)} MB). Maksimal 5 MB.`;
        return;
    }

    form.photo = file;
    selectedFileName.value = file.name;
    selectedFileSize.value = `${(file.size / 1024).toFixed(1)} KB`;
    photoPreview.value = URL.createObjectURL(file);
};

const handlePhotoChange = (event: Event) => {
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

const removeSelectedPhoto = () => {
    form.photo = null;
    selectedFileName.value = null;
    selectedFileSize.value = null;
    clientPhotoError.value = null;
    photoPreview.value = null;
    if (fileInputRef.value) {
        fileInputRef.value.value = '';
    }
};

const submit = () => {
    clientPhotoError.value = null;
    form.post(route('admin.apparatus.store'), {
        preserveScroll: true,
        forceFormData: true,
    });
};
</script>

<template>
    <AdminLayout>
        <Head title="Tambah Perangkat Desa" />

        <div class="space-y-6 max-w-4xl mx-auto pb-12">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <Link :href="route('admin.apparatus.index')">
                        <Button variant="ghost" size="sm" class="h-9 w-9 p-0 text-gray-500 hover:text-gray-900 dark:hover:text-white">
                            <ArrowLeft class="w-5 h-5" />
                        </Button>
                    </Link>
                    <PageTitle 
                        title="Tambah Perangkat Desa" 
                        subtitle="Tambahkan data pamong atau staf pemerintahan desa baru" 
                    />
                </div>
            </div>

            <!-- Form Card -->
            <form @submit.prevent="submit" class="space-y-6">
                <div class="bg-white dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-800 shadow-sm p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-start">
                        <!-- Photo Upload Column -->
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
                            <div 
                                class="relative w-36 h-44 rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 overflow-hidden shadow-sm flex items-center justify-center cursor-pointer group"
                                @click="openFilePicker"
                            >
                                <img 
                                    v-if="photoPreview" 
                                    :src="photoPreview" 
                                    alt="Foto Perangkat" 
                                    class="w-full h-full object-cover transition-transform duration-200 group-hover:scale-105"
                                />
                                <div v-else class="flex flex-col items-center justify-center text-gray-400 p-2">
                                    <User class="w-12 h-12 stroke-1 text-gray-300 dark:text-gray-600 mb-1" />
                                    <span class="text-xs font-medium text-gray-500">Pilih Foto</span>
                                </div>

                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white text-xs font-medium">
                                    Klik upload foto
                                </div>
                            </div>

                            <div v-if="selectedFileName" class="text-center space-y-1">
                                <p class="text-xs font-medium text-emerald-700 dark:text-emerald-400 truncate max-w-[200px]" :title="selectedFileName">
                                    {{ selectedFileName }}
                                </p>
                                <p class="text-[11px] text-gray-400">{{ selectedFileSize }}</p>
                            </div>

                            <div class="flex flex-col gap-2 w-full max-w-[200px]">
                                <Button 
                                    type="button" 
                                    variant="outline" 
                                    size="sm"
                                    class="w-full text-xs font-medium flex items-center justify-center gap-1.5 border-emerald-600/40 text-emerald-700 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-950/30"
                                    @click="openFilePicker"
                                >
                                    <Upload class="w-3.5 h-3.5" />
                                    <span>{{ photoPreview ? 'Ganti Foto' : 'Upload Foto' }}</span>
                                </Button>

                                <Button 
                                    v-if="form.photo"
                                    type="button" 
                                    variant="ghost" 
                                    size="sm"
                                    class="w-full text-[11px] text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/30 h-7"
                                    @click="removeSelectedPhoto"
                                >
                                    Batalkan Foto
                                </Button>
                            </div>

                            <input 
                                ref="fileInputRef"
                                id="photo-input" 
                                type="file" 
                                accept="image/png,image/jpeg,image/jpg,image/webp"
                                class="hidden" 
                                @change="handlePhotoChange"
                            />
                            
                            <p class="text-[11px] text-gray-400">PNG, JPG, WEBP maks 5MB</p>
                            <span v-if="clientPhotoError || form.errors.photo" class="text-xs text-red-500 font-medium leading-tight">
                                {{ clientPhotoError || form.errors.photo }}
                            </span>
                        </div>

                        <!-- Basic Information Fields -->
                        <div class="md:col-span-2 space-y-4">
                            <div class="space-y-1.5">
                                <Label for="name" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Nama Lengkap & Gelar <span class="text-red-500">*</span></Label>
                                <Input 
                                    id="name" 
                                    v-model="form.name" 
                                    placeholder="cth: H. Ahmad Subardjo, S.IP" 
                                    class="h-10"
                                    :class="{ 'border-red-500': form.errors.name }"
                                />
                                <span v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</span>
                            </div>

                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between">
                                    <Label for="position_id" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Jabatan <span class="text-red-500">*</span></Label>
                                    <button 
                                        type="button" 
                                        @click="positionsModalOpen = true"
                                        class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 hover:underline inline-flex items-center gap-1"
                                    >
                                        <Plus class="w-3.5 h-3.5" />
                                        <span>+ Tambah / Kelola Jabatan</span>
                                    </button>
                                </div>
                                <select 
                                    id="position_id"
                                    v-model="form.position_id"
                                    class="w-full h-10 px-3 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                                    :class="{ 'border-red-500': form.errors.position_id }"
                                >
                                    <option value="" disabled>-- Pilih Jabatan --</option>
                                    <option v-for="pos in localPositions" :key="pos.id" :value="pos.id">
                                        {{ pos.name }} (Urutan: {{ pos.order }})
                                    </option>
                                </select>
                                <span v-if="form.errors.position_id" class="text-xs text-red-500">{{ form.errors.position_id }}</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <Label for="nip" class="text-xs font-semibold text-gray-700 dark:text-gray-300">NIP / Nomor Identitas</Label>
                                    <Input 
                                        id="nip" 
                                        v-model="form.nip" 
                                        placeholder="cth: 19820512 200801 1 004" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.nip }"
                                    />
                                    <span v-if="form.errors.nip" class="text-xs text-red-500">{{ form.errors.nip }}</span>
                                </div>

                                <div class="space-y-1.5">
                                    <Label for="education" class="text-xs font-semibold text-gray-700 dark:text-gray-300">Pendidikan Terakhir</Label>
                                    <Input 
                                        id="education" 
                                        v-model="form.education" 
                                        placeholder="cth: S1 Ilmu Pemerintahan" 
                                        class="h-10"
                                        :class="{ 'border-red-500': form.errors.education }"
                                    />
                                    <span v-if="form.errors.education" class="text-xs text-red-500">{{ form.errors.education }}</span>
                                </div>
                            </div>

                            <!-- Active Checkbox -->
                            <div class="pt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input 
                                        type="checkbox" 
                                        v-model="form.is_active"
                                        class="w-4 h-4 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500"
                                    />
                                    <div>
                                        <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">Status Aktif Menjabat</span>
                                        <p class="text-xs text-gray-400">Centang jika aparatur sedang aktif menjalankan masa jabatan.</p>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-end gap-3 pt-2">
                    <Link :href="route('admin.apparatus.index')">
                        <Button type="button" variant="outline">
                            Batal
                        </Button>
                    </Link>

                    <Button 
                        type="submit" 
                        variant="default"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm flex items-center gap-2"
                        :disabled="form.processing"
                    >
                        <Loader2 v-if="form.processing" class="w-4 h-4 animate-spin" />
                        <Save v-else class="w-4 h-4" />
                        <span>{{ form.processing ? 'Menyimpan...' : 'Simpan Perangkat Desa' }}</span>
                    </Button>
                </div>
            </form>
        </div>

        <!-- Manage Positions Modal -->
        <ManagePositionsModal 
            v-model:open="positionsModalOpen" 
            :positions="localPositions" 
            @positions-changed="onPositionsChanged"
            @position-created="onPositionCreated"
        />
    </AdminLayout>
</template>
