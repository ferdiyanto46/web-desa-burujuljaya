<script setup lang="ts">
import { InboxIcon } from 'lucide-vue-next';

interface Props {
    title?: string;
    description?: string;
}

withDefaults(defineProps<Props>(), {
    title: 'Tidak ada data',
    description: 'Belum ada data yang tersedia saat ini.',
});
</script>

<template>
    <div class="flex flex-col items-center justify-center rounded-lg border border-dashed border-gray-300 p-12 text-center dark:border-gray-700">
        <InboxIcon class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" />
        <h3 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
            {{ title }}
        </h3>
        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            {{ description }}
        </p>
        <div class="mt-6" v-if="$slots.action">
            <slot name="action" />
        </div>
    </div>
</template>
