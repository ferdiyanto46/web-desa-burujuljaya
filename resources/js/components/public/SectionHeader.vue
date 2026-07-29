<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
    align?: 'left' | 'center' | 'right';
}>();
</script>

<template>
    <div :class="['mb-8', {
        'text-left': align === 'left' || !align,
        'text-center': align === 'center',
        'text-right': align === 'right'
    }]">
        <h2 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white relative inline-block">
            {{ title }}
            <span class="absolute -bottom-2 left-0 w-1/2 h-1 bg-blue-600 dark:bg-blue-500 rounded-full" :class="{'left-1/4 right-1/4': align === 'center', 'left-auto right-0': align === 'right'}"></span>
        </h2>
        <p v-if="subtitle" class="mt-4 max-w-2xl text-base text-gray-500 dark:text-gray-400" :class="{'mx-auto': align === 'center', 'ml-auto': align === 'right'}">
            {{ subtitle }}
        </p>
    </div>
</template>
