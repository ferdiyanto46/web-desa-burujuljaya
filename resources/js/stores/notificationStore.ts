import { defineStore } from 'pinia';
import { ref } from 'vue';

export interface Toast {
    id: string;
    message: string;
    type: 'success' | 'error' | 'warning' | 'info';
    duration?: number;
}

export const useNotificationStore = defineStore('notification', () => {
    const toasts = ref<Toast[]>([]);
    const isLoading = ref(false);

    const addToast = (toast: Omit<Toast, 'id'>) => {
        const id = Math.random().toString(36).substring(2, 9);
        const newToast = { ...toast, id };
        toasts.value.push(newToast);

        if (toast.duration !== 0) {
            setTimeout(() => {
                removeToast(id);
            }, toast.duration || 3000);
        }
    };

    const removeToast = (id: string) => {
        toasts.value = toasts.value.filter((t) => t.id !== id);
    };

    const setLoading = (value: boolean) => {
        isLoading.value = value;
    };

    return {
        toasts,
        isLoading,
        addToast,
        removeToast,
        setLoading,
    };
});
