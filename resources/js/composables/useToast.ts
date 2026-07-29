import { toast as sonnerToast } from 'vue-sonner';
import { useNotificationStore } from '@/stores/notificationStore';

export function useToast() {
    const notificationStore = useNotificationStore();

    const success = (message: string, description?: string) => {
        sonnerToast.success(message, { description });
        notificationStore.addToast({ message, type: 'success' });
    };

    const error = (message: string, description?: string) => {
        sonnerToast.error(message, { description });
        notificationStore.addToast({ message, type: 'error' });
    };

    const info = (message: string, description?: string) => {
        sonnerToast.info(message, { description });
        notificationStore.addToast({ message, type: 'info' });
    };

    const warning = (message: string, description?: string) => {
        sonnerToast.warning(message, { description });
        notificationStore.addToast({ message, type: 'warning' });
    };

    return {
        success,
        error,
        info,
        warning,
    };
}
