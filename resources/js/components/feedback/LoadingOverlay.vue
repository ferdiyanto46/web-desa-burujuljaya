<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import { ref, onMounted, onUnmounted } from 'vue';
import { Icons } from '@/constants/icons';

const isLoading = ref(false);
let timeout: ReturnType<typeof setTimeout> | null = null;

const startLoading = () => {
    // Optional delay to prevent flickering for fast requests
    timeout = setTimeout(() => {
        isLoading.value = true;
    }, 150);
};

const stopLoading = () => {
    if (timeout) {
clearTimeout(timeout);
}

    isLoading.value = false;
};

const cleanups: Array<() => void> = [];

onMounted(() => {
    cleanups.push(router.on('start', startLoading));
    cleanups.push(router.on('finish', stopLoading));
    cleanups.push(router.on('success', stopLoading));
    cleanups.push(router.on('error', stopLoading));
    cleanups.push(router.on('cancel', stopLoading));
});

onUnmounted(() => {
    cleanups.forEach(cleanup => cleanup());

    if (timeout) {
clearTimeout(timeout);
}
});
</script>

<template>
    <Transition
        enter-active-class="transition-opacity duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-300"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
    >
        <div v-if="isLoading" class="fixed inset-0 z-[100] flex items-center justify-center bg-white/60 dark:bg-gray-900/60 backdrop-blur-sm" aria-busy="true" aria-label="Memuat data">
            <div class="flex flex-col items-center gap-4 p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-100 dark:border-gray-700">
                <component :is="Icons.SearchIcon" class="w-8 h-8 text-blue-600 dark:text-blue-400 animate-spin" />
                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Memuat...</span>
            </div>
        </div>
    </Transition>
</template>
