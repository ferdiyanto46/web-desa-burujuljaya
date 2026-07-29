<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-4">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ title }}
        </h2>
        <p v-if="description" class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            {{ description }}
        </p>
    </div>
</template>
