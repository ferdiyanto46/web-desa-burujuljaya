<script setup lang="ts">
import { 
    AlertCircle, 
    Check, 
    Edit, 
    Layers, 
    Loader2, 
    Plus, 
    Trash2, 
    X 
} from 'lucide-vue-next';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

export interface ApparatusPosition {
    id: number;
    name: string;
    order: number;
    apparatuses_count?: number;
}

const props = defineProps<{
    open: boolean;
    positions: ApparatusPosition[];
}>();

const emit = defineEmits<{
    (e: 'update:open', value: boolean): void;
    (e: 'positions-changed', positions: ApparatusPosition[]): void;
    (e: 'position-created', position: ApparatusPosition): void;
}>();

// Form State
const editingId = ref<number | null>(null);
const name = ref('');
const order = ref<number | null>(null);
const errorMessage = ref<string | null>(null);
const successMessage = ref<string | null>(null);
const isSubmitting = ref(false);
const isDeletingId = ref<number | null>(null);
const localPositions = ref<ApparatusPosition[]>([...props.positions]);

watch(() => props.positions, (newVal) => {
    localPositions.value = [...newVal].sort((a, b) => a.order - b.order);
}, { deep: true });

const getCsrfToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement | null;
    return meta ? meta.content : '';
};

const resetForm = () => {
    editingId.value = null;
    name.value = '';
    const maxOrder = localPositions.value.length > 0 ? Math.max(...localPositions.value.map(p => p.order)) : 0;
    order.value = maxOrder + 1;
    errorMessage.value = null;
    successMessage.value = null;
};

watch(() => props.open, (isOpen) => {
    if (isOpen) {
        localPositions.value = [...props.positions].sort((a, b) => a.order - b.order);
        resetForm();
    }
});

const startEdit = (pos: ApparatusPosition) => {
    editingId.value = pos.id;
    name.value = pos.name;
    order.value = pos.order;
    errorMessage.value = null;
    successMessage.value = null;
};

const cancelEdit = () => {
    resetForm();
};

const handleSave = async () => {
    errorMessage.value = null;
    successMessage.value = null;

    if (!name.value.trim()) {
        errorMessage.value = 'Nama jabatan tidak boleh kosong.';
        return;
    }

    isSubmitting.value = true;
    const csrfToken = getCsrfToken();

    try {
        if (editingId.value) {
            // Update Position via Fetch API
            const response = await fetch(`/admin/apparatus-positions/${editingId.value}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    name: name.value.trim(),
                    order: order.value ?? 1,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || data.error || (data.errors ? Object.values(data.errors)[0] as string : 'Gagal memperbarui jabatan.'));
            }

            // Update in local state
            const index = localPositions.value.findIndex(p => p.id === editingId.value);
            if (index !== -1) {
                localPositions.value[index] = {
                    ...localPositions.value[index],
                    name: data.data.name,
                    order: data.data.order,
                };
                localPositions.value.sort((a, b) => a.order - b.order);
            }

            emit('positions-changed', localPositions.value);
            successMessage.value = `Jabatan "${data.data.name}" berhasil diperbarui.`;
            resetForm();
        } else {
            // Create Position via Fetch API
            const response = await fetch('/admin/apparatus-positions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    name: name.value.trim(),
                    order: order.value ?? undefined,
                }),
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || data.error || (data.errors ? Object.values(data.errors)[0] as string : 'Gagal menambahkan jabatan.'));
            }

            const newPos: ApparatusPosition = {
                id: data.data.id,
                name: data.data.name,
                order: data.data.order,
                apparatuses_count: 0,
            };

            localPositions.value.push(newPos);
            localPositions.value.sort((a, b) => a.order - b.order);

            emit('positions-changed', localPositions.value);
            emit('position-created', newPos);
            successMessage.value = `Jabatan "${newPos.name}" berhasil ditambahkan!`;
            resetForm();
        }
    } catch (err: any) {
        errorMessage.value = err.message || 'Terjadi kesalahan saat memproses data.';
    } finally {
        isSubmitting.value = false;
    }
};

const handleDelete = async (pos: ApparatusPosition) => {
    if (pos.apparatuses_count && pos.apparatuses_count > 0) {
        alert(`Jabatan "${pos.name}" sedang digunakan oleh ${pos.apparatuses_count} perangkat desa. Silakan pindahkan atau ubah jabatan perangkat terkait terlebih dahulu.`);
        return;
    }

    if (!confirm(`Apakah Anda yakin ingin menghapus jabatan "${pos.name}"?`)) {
        return;
    }

    isDeletingId.value = pos.id;
    const csrfToken = getCsrfToken();

    try {
        const response = await fetch(`/admin/apparatus-positions/${pos.id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
            },
        });

        const data = await response.json();

        if (!response.ok) {
            if (response.status === 404) {
                localPositions.value = localPositions.value.filter(p => p.id !== pos.id);
                emit('positions-changed', localPositions.value);
                throw new Error(`Jabatan "${pos.name}" sudah tidak ada atau telah dihapus sebelumnya.`);
            }
            throw new Error(data.message || data.error || 'Gagal menghapus jabatan.');
        }

        localPositions.value = localPositions.value.filter(p => p.id !== pos.id);
        emit('positions-changed', localPositions.value);

        if (editingId.value === pos.id) {
            resetForm();
        }
    } catch (err: any) {
        alert(err.message || 'Gagal menghapus jabatan.');
    } finally {
        isDeletingId.value = null;
    }
};
</script>

<template>
    <Teleport to="body">
        <div 
            v-if="open" 
            class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-200"
            @click.self="emit('update:open', false)"
        >
            <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 w-full max-w-2xl overflow-hidden flex flex-col max-h-[90vh]">
                
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between bg-gray-50/50 dark:bg-gray-800/40">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                            <Layers class="w-4 h-4" />
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base">Kelola Jabatan & Struktur Organisasi</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Atur daftar jabatan dan urutan hirarki dalam struktur perangkat desa.</p>
                        </div>
                    </div>
                    <button 
                        type="button" 
                        @click="emit('update:open', false)"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors"
                    >
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <!-- Body -->
                <div class="p-6 space-y-6 overflow-y-auto flex-1">
                    
                    <!-- Form Input (Add / Edit) -->
                    <div class="p-4 rounded-xl border border-emerald-100 dark:border-emerald-950/50 bg-emerald-50/30 dark:bg-emerald-950/20 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300">
                                {{ editingId ? 'Edit Nama / Urutan Jabatan' : 'Tambah Jabatan Baru' }}
                            </span>
                            <button 
                                v-if="editingId" 
                                type="button" 
                                @click="cancelEdit" 
                                class="text-xs text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 underline"
                            >
                                Batal Edit
                            </button>
                        </div>

                        <!-- Using div instead of form tag to strictly avoid any outer form interference -->
                        <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-end">
                            <div class="sm:col-span-7 space-y-1">
                                <Label class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">
                                    Nama Jabatan <span class="text-red-500">*</span>
                                </Label>
                                <Input 
                                    v-model="name"
                                    type="text"
                                    placeholder="cth: Kepala Seksi Kesejahteraan"
                                    class="h-9 text-xs"
                                    :disabled="isSubmitting"
                                    @keydown.enter.prevent="handleSave"
                                />
                            </div>

                            <div class="sm:col-span-2 space-y-1">
                                <Label class="text-[11px] font-semibold text-gray-700 dark:text-gray-300">
                                    Urutan
                                </Label>
                                <Input 
                                    v-model.number="order"
                                    type="number"
                                    min="0"
                                    placeholder="1"
                                    class="h-9 text-xs"
                                    :disabled="isSubmitting"
                                    @keydown.enter.prevent="handleSave"
                                />
                            </div>

                            <div class="sm:col-span-3">
                                <Button 
                                    type="button" 
                                    size="sm" 
                                    class="w-full h-9 text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white flex items-center justify-center gap-1.5"
                                    :disabled="isSubmitting"
                                    @click="handleSave"
                                >
                                    <Loader2 v-if="isSubmitting" class="w-3.5 h-3.5 animate-spin" />
                                    <Check v-else-if="editingId" class="w-3.5 h-3.5" />
                                    <Plus v-else class="w-3.5 h-3.5" />
                                    <span>{{ editingId ? 'Simpan' : 'Tambah' }}</span>
                                </Button>
                            </div>
                        </div>

                        <p v-if="errorMessage" class="text-xs text-red-600 dark:text-red-400 flex items-center gap-1">
                            <AlertCircle class="w-3.5 h-3.5 shrink-0" />
                            <span>{{ errorMessage }}</span>
                        </p>

                        <p v-if="successMessage" class="text-xs text-emerald-600 dark:text-emerald-400 flex items-center gap-1 font-medium">
                            <Check class="w-3.5 h-3.5 shrink-0" />
                            <span>{{ successMessage }}</span>
                        </p>
                    </div>

                    <!-- Table Positions -->
                    <div class="border border-gray-200 dark:border-gray-800 rounded-xl overflow-hidden">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-gray-50 dark:bg-gray-800/60 text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-800">
                                <tr>
                                    <th class="py-2.5 px-3 font-semibold w-16 text-center">Urutan</th>
                                    <th class="py-2.5 px-3 font-semibold">Nama Jabatan</th>
                                    <th class="py-2.5 px-3 font-semibold text-center w-28">Jumlah Anggota</th>
                                    <th class="py-2.5 px-3 font-semibold text-right w-24">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                <tr 
                                    v-for="pos in localPositions" 
                                    :key="pos.id"
                                    class="hover:bg-gray-50/70 dark:hover:bg-gray-800/40 transition-colors"
                                    :class="{ 'bg-emerald-50/50 dark:bg-emerald-950/30 font-medium': editingId === pos.id }"
                                >
                                    <td class="py-2.5 px-3 text-center font-mono text-gray-500">
                                        {{ pos.order }}
                                    </td>
                                    <td class="py-2.5 px-3 text-gray-900 dark:text-white font-medium">
                                        {{ pos.name }}
                                    </td>
                                    <td class="py-2.5 px-3 text-center">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                            {{ pos.apparatuses_count ?? 0 }} orang
                                        </span>
                                    </td>
                                    <td class="py-2.5 px-3 text-right">
                                        <div class="flex items-center justify-end gap-1">
                                            <button 
                                                type="button" 
                                                @click="startEdit(pos)"
                                                class="p-1 rounded hover:bg-gray-200 dark:hover:bg-gray-700 text-blue-600 dark:text-blue-400 transition-colors"
                                                title="Edit Jabatan"
                                            >
                                                <Edit class="w-3.5 h-3.5" />
                                            </button>
                                            <button 
                                                type="button" 
                                                @click="handleDelete(pos)"
                                                :disabled="isDeletingId === pos.id"
                                                class="p-1 rounded hover:bg-red-100 dark:hover:bg-red-950/50 text-red-600 dark:text-red-400 transition-colors disabled:opacity-50"
                                                title="Hapus Jabatan"
                                            >
                                                <Loader2 v-if="isDeletingId === pos.id" class="w-3.5 h-3.5 animate-spin" />
                                                <Trash2 v-else class="w-3.5 h-3.5" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>

                                <tr v-if="localPositions.length === 0">
                                    <td colspan="4" class="py-6 text-center text-gray-400">
                                        Belum ada data jabatan.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <!-- Footer -->
                <div class="px-6 py-3 border-t border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/40 flex justify-end">
                    <Button 
                        type="button" 
                        variant="outline" 
                        size="sm" 
                        @click="emit('update:open', false)"
                        class="text-xs"
                    >
                        Tutup
                    </Button>
                </div>

            </div>
        </div>
    </Teleport>
</template>
