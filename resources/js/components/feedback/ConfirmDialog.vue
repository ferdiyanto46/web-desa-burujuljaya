<script setup lang="ts">
import AppButton from '@/components/atoms/AppButton.vue';
import AppModal from './AppModal.vue';

interface Props {
    isOpen: boolean;
    title: string;
    message: string;
    confirmText?: string;
    cancelText?: string;
    type?: 'danger' | 'warning' | 'info';
    processing?: boolean;
}

withDefaults(defineProps<Props>(), {
    confirmText: 'Konfirmasi',
    cancelText: 'Batal',
    type: 'warning',
    processing: false,
});

const emit = defineEmits<{
    (e: 'close'): void;
    (e: 'confirm'): void;
}>();
</script>

<template>
    <AppModal :is-open="isOpen" :title="title" @close="emit('close')" max-width="md">
        <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full sm:mx-0 sm:h-10 sm:w-10"
                :class="[
                    type === 'danger' ? 'bg-red-100 dark:bg-red-900/30' : 
                    type === 'info' ? 'bg-blue-100 dark:bg-blue-900/30' : 
                    'bg-yellow-100 dark:bg-yellow-900/30'
                ]"
            >
                <svg v-if="type === 'danger'" class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <svg v-else-if="type === 'info'" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <svg v-else class="h-6 w-6 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                <div class="mt-2">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ message }}
                    </p>
                </div>
            </div>
        </div>
        
        <template #footer>
            <AppButton 
                :variant="type === 'danger' ? 'danger' : type === 'info' ? 'primary' : 'warning'" 
                :loading="processing"
                @click="emit('confirm')"
                class="w-full sm:ml-3 sm:w-auto"
            >
                {{ confirmText }}
            </AppButton>
            <AppButton 
                variant="outline" 
                @click="emit('close')"
                :disabled="processing"
                class="mt-3 w-full sm:mt-0 sm:w-auto"
            >
                {{ cancelText }}
            </AppButton>
        </template>
    </AppModal>
</template>
