<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
    align?: 'left' | 'center' | 'right';
}>();
</script>

<template>
    <div :class="['mb-10', {
        'text-left': align === 'left' || !align,
        'text-center': align === 'center',
        'text-right': align === 'right'
    }]">
        <div class="inline-block relative">
            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-gray-900 dark:text-white">
                {{ title }}
            </h2>
            <div 
                class="h-1 bg-emerald-600 dark:bg-emerald-500 rounded-full mt-2.5" 
                :class="{
                    'w-16': align === 'left' || !align,
                    'w-20 mx-auto': align === 'center',
                    'w-16 ml-auto': align === 'right'
                }"
            ></div>
        </div>
        <p v-if="subtitle" class="mt-3 max-w-2xl text-sm sm:text-base text-gray-500 dark:text-gray-400" :class="{'mx-auto': align === 'center', 'ml-auto': align === 'right'}">
            {{ subtitle }}
        </p>
    </div>
</template>
