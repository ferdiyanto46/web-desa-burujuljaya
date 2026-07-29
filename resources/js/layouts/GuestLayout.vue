<script setup lang="ts">
import { Toaster } from 'vue-sonner';
import { useLayoutStore } from '@/stores/layoutStore';

const layoutStore = useLayoutStore();
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8 dark:bg-gray-900 transition-colors duration-300">
        <div class="absolute top-4 right-4">
            <button @click="layoutStore.toggleDarkMode()" class="p-2 rounded-full text-gray-500 hover:bg-gray-200 dark:text-gray-400 dark:hover:bg-gray-700">
                <svg v-if="layoutStore.isDarkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        <div class="w-full max-w-md space-y-8">
            <div class="text-center">
                <slot name="logo">
                    <h2 class="mt-6 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">
                        Authentication
                    </h2>
                </slot>
            </div>
            
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 dark:bg-gray-800 border dark:border-gray-700">
                <slot />
            </div>
        </div>

        <!-- Toast Notifications -->
        <Toaster position="top-right" rich-colors />
    </div>
</template>
