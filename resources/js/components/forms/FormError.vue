<script setup lang="ts">
interface Props {
    message?: string;
}

defineProps<Props>();
</script>

<template>
    <div v-show="message" class="mt-1">
        <p class="text-sm font-medium text-red-600 dark:text-red-500">
            {{ message }}
        </p>
    </div>
</template>
