<script setup lang="ts">
import { ref, watch } from 'vue';
import Dropzone from './Dropzone.vue';
import ImagePreview from './ImagePreview.vue';
import FormError from '../../forms/FormError.vue';

interface Props {
    modelValue: File | null;
    imageUrl?: string | null;
    label?: string;
    helperText?: string;
    error?: string;
    disabled?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: null,
    imageUrl: null,
    disabled: false,
    label: 'Upload Gambar',
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

const removeImage = () => {
    currentFile.value = null;
    emit('update:modelValue', null);
};
</script>

<template>
    <div class="w-full">
        <label v-if="label" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
        </label>
        
        <div v-if="currentFile || imageUrl" class="mb-4">
            <ImagePreview 
                :file="currentFile" 
                :src="imageUrl" 
                @remove="removeImage" 
                :removable="!disabled"
            />
        </div>
        
        <Dropzone 
            v-if="!currentFile && !imageUrl"
            accept="image/*"
            :disabled="disabled"
            :helperText="helperText"
            @drop="handleDrop"
            @error="handleError"
        />
        
        <FormError :message="error" />
    </div>
</template>
