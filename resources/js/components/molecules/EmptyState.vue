<script setup lang="ts">
import AppButton from '@/components/atoms/AppButton.vue';

interface Props {
    title: string;
    description?: string;
    icon?: string;
    actionLabel?: string;
}

defineProps<Props>();
const emit = defineEmits<{
    (e: 'action'): void;
}>();
</script>

<template>
    <div class="text-center py-12 px-4 sm:px-6 lg:px-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
        </svg>
        <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
        <p v-if="description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ description }}</p>
        <div v-if="actionLabel" class="mt-6">
            <AppButton variant="primary" @click="emit('action')">
                <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                </svg>
                {{ actionLabel }}
            </AppButton>
        </div>
    </div>
</template>
