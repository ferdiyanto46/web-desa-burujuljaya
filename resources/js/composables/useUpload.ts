import { ref } from 'vue';
import { useToast } from './useToast';

interface UploadOptions {
    maxSizeMB?: number;
    acceptedTypes?: string[];
}

export function useUpload(options: UploadOptions = {}) {
    const { maxSizeMB = 2, acceptedTypes = ['image/jpeg', 'image/png', 'image/webp'] } = options;
    const toast = useToast();
    
    const file = ref<File | null>(null);
    const previewUrl = ref<string | null>(null);

    const handleFileChange = (e: Event) => {
        const target = e.target as HTMLInputElement;
        const selectedFile = target.files?.[0];
        
        if (!selectedFile) {
            clearFile();

            return false;
        }

        // Validate size
        if (selectedFile.size > maxSizeMB * 1024 * 1024) {
            toast.error(`Ukuran file maksimal ${maxSizeMB}MB`);
            target.value = '';

            return false;
        }

        // Validate type
        if (acceptedTypes.length > 0 && !acceptedTypes.includes(selectedFile.type)) {
            toast.error('Format file tidak didukung');
            target.value = '';

            return false;
        }

        file.value = selectedFile;
        previewUrl.value = URL.createObjectURL(selectedFile);

        return true;
    };

    const clearFile = () => {
        file.value = null;

        if (previewUrl.value) {
            URL.revokeObjectURL(previewUrl.value);
            previewUrl.value = null;
        }
    };

    return {
        file,
        previewUrl,
        handleFileChange,
        clearFile,
    };
}
