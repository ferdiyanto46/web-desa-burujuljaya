<script setup lang="ts">
interface Props {
    title: string;
    description?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="mb-4">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ title }}
        </h2>
        <p v-if="description" class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ description }}
        </p>
    </div>
</template>
