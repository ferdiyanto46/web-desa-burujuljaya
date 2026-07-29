<script setup lang="ts">
import { ref, watch } from 'vue';
import Dropzone from './Dropzone.vue';
import FormError from '../../forms/FormError.vue';
import { FileIcon, XIcon } from 'lucide-vue-next';

interface Props {
    modelValue: File | null;
    fileUrl?: string | null;
    fileName?: string | null;
    label?: string;
    helperText?: string;
    accept?: string;
    error?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: null,
    fileUrl: null,
    fileName: null,
    disabled: false,
    label: 'Upload File',
    accept: '*',
    helperText: 'Seret file ke sini atau klik untuk memilih',
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: File | null): void;
    (e: 'error', message: string): void;
}>();

const currentFile = ref<File | null>(props.modelValue);

watch(() => props.modelValue, (newVal) => {
    currentFile.value = newVal;
});

const handleDrop = (files: FileList | File[]) => {
    if (files.length > 0) {
        currentFile.value = files[0] as File;
        emit('update:modelValue', currentFile.value);
    }
};

const handleError = (message: string) => {
    emit('error', message);
};

const removeFile = () => {
    currentFile.value = null;
    emit('update:modelValue', null);
};

const formatBytes = (bytes: number, decimals = 2) => {
    if (!+bytes) return '0 Bytes';
    const k = 1024;
    const dm = decimals < 0 ? 0 : decimals;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
};
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
        </label>
        
        <div v-if="currentFile || fileUrl" class="mb-4">
            <div class="flex items-center justify-between rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-800">
                <div class="flex items-center space-x-3 overflow-hidden">
                    <div class="rounded-full bg-blue-100 p-2 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                        <FileIcon class="h-5 w-5" />
                    </div>
                    <div class="flex flex-col truncate">
                        <span class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ currentFile?.name || fileName || 'File terlampir' }}
                        </span>
                        <span v-if="currentFile?.size" class="text-xs text-gray-500 dark:text-gray-400">
                            {{ formatBytes(currentFile.size) }}
                        </span>
                    </div>
                </div>
                <button 
                    v-if="!disabled"
                    type="button"
                    @click.prevent="removeFile"
                    class="ml-4 flex-shrink-0 rounded-full p-1.5 text-gray-500 hover:bg-gray-200 hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-100"
                    title="Hapus file"
                >
                    <XIcon class="h-4 w-4" />
                </button>
            </div>
        </div>
        
        <Dropzone 
            v-if="!currentFile && !fileUrl"
            :accept="accept"
            :disabled="disabled"
            :helperText="helperText"
            :label="'Pilih File'"
            @drop="handleDrop"
            @error="handleError"
        />
        
        <FormError :message="error" />
    </div>
</template>
