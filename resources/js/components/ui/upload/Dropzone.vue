<script setup lang="ts">
import { ref } from 'vue';
import { UploadCloudIcon } from 'lucide-vue-next';

interface Props {
    accept?: string;
    maxSize?: number; // in bytes
    disabled?: boolean;
    multiple?: boolean;
    label?: string;
    helperText?: string;
}

const props = withDefaults(defineProps<Props>(), {
    accept: 'image/*',
    maxSize: 5 * 1024 * 1024, // 5MB
    disabled: false,
    multiple: false,
    label: 'Klik atau seret file ke area ini untuk mengunggah',
    helperText: 'Mendukung PNG, JPG atau GIF (Maks. 5MB)',
});

const emit = defineEmits<{
    (e: 'drop', files: FileList | File[]): void;
    (e: 'error', message: string): void;
}>();

const isDragging = ref(false);
const fileInput = ref<HTMLInputElement | null>(null);

const onDragOver = (e: DragEvent) => {
    e.preventDefault();
    if (!props.disabled) isDragging.value = true;
};

const onDragLeave = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = false;
};

const validateFiles = (files: FileList | File[] | null) => {
    if (!files || files.length === 0) return;
    
    const validFiles: File[] = [];
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        // Simple type validation (could be improved for robust accept parsing)
        if (props.accept !== '*' && !file.type.match(props.accept.replace('*', '.*'))) {
            emit('error', `Tipe file ${file.name} tidak diizinkan.`);
            continue;
        }
        
        if (file.size > props.maxSize) {
            emit('error', `Ukuran file ${file.name} terlalu besar (maks ${(props.maxSize / 1024 / 1024).toFixed(1)}MB).`);
            continue;
        }
        
        validFiles.push(file);
        
        if (!props.multiple) break; // Only take first file if not multiple
    }
    
    if (validFiles.length > 0) {
        emit('drop', props.multiple ? validFiles : [validFiles[0]]);
    }
};

const onDrop = (e: DragEvent) => {
    e.preventDefault();
    isDragging.value = false;
    if (props.disabled) return;
    
    validateFiles(e.dataTransfer?.files || null);
};

const onChange = (e: Event) => {
    const target = e.target as HTMLInputElement;
    validateFiles(target.files);
    // Reset input so same file can be selected again
    if (target) target.value = '';
};

const triggerClick = () => {
    if (!props.disabled && fileInput.value) {
        fileInput.value.click();
    }
};
</script>

<template>
    <div
        @dragover="onDragOver"
        @dragleave="onDragLeave"
        @drop="onDrop"
        @click="triggerClick"
        class="group relative flex cursor-pointer flex-col items-center justify-center rounded-xl border-2 border-dashed p-8 text-center transition-all duration-200"
        :class="[
            disabled 
                ? 'cursor-not-allowed border-gray-200 bg-gray-50 opacity-60 dark:border-gray-800 dark:bg-gray-900/50' 
                : isDragging 
                    ? 'border-blue-500 bg-blue-50 dark:border-blue-500 dark:bg-blue-900/20' 
                    : 'border-gray-300 bg-white hover:border-gray-400 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:hover:border-gray-600 dark:hover:bg-gray-800/80'
        ]"
    >
        <input
            ref="fileInput"
            type="file"
            class="hidden"
            :accept="accept"
            :multiple="multiple"
            :disabled="disabled"
            @change="onChange"
        />
        
        <div class="rounded-full bg-blue-100 p-3 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400" :class="{ 'bg-gray-100 text-gray-400 dark:bg-gray-800 dark:text-gray-500': disabled }">
            <UploadCloudIcon class="h-8 w-8" />
        </div>
        
        <p class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">
            {{ label }}
        </p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            {{ helperText }}
        </p>
    </div>
</template>
