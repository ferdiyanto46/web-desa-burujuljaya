import { ref } from 'vue';

interface ConfirmOptions {
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    type?: 'danger' | 'warning' | 'info';
    onConfirm: () => void | Promise<void>;
    onCancel?: () => void;
}

export function useConfirm() {
    const isOpen = ref(false);
    const options = ref<ConfirmOptions | null>(null);
    const isProcessing = ref(false);

    const requireConfirmation = (opts: ConfirmOptions) => {
        options.value = {
            confirmText: 'Konfirmasi',
            cancelText: 'Batal',
            type: 'warning',
            ...opts,
        };
        isOpen.value = true;
    };

    const confirm = async () => {
        if (!options.value) {
return;
}
        
        isProcessing.value = true;

        try {
            await options.value.onConfirm();
        } finally {
            isProcessing.value = false;
            isOpen.value = false;
        }
    };

    const cancel = () => {
        if (options.value?.onCancel) {
            options.value.onCancel();
        }

        isOpen.value = false;
    };

    return {
        isOpen,
        options,
        isProcessing,
        requireConfirmation,
        confirm,
        cancel,
    };
}
