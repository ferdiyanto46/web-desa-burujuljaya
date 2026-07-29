<script setup lang="ts">
defineProps<{
    title: string;
    subtitle?: string;
    backgroundImage?: string;
}>();
</script>

<template>
    <div class="relative bg-blue-900 overflow-hidden" :class="{'py-16 sm:py-24': !backgroundImage, 'py-24 sm:py-32': backgroundImage}">
        <!-- Background Image with Overlay -->
        <div v-if="backgroundImage" class="absolute inset-0">
            <img :src="backgroundImage" :alt="title" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-blue-900/80 mix-blend-multiply" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/90 via-blue-900/40 opacity-80" aria-hidden="true"></div>
        </div>
        
        <!-- Default Pattern if no image -->
        <div v-else class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" fill="none" viewBox="0 0 100 100" preserveAspectRatio="none">
                <pattern id="pattern-grid" x="0" y="0" width="10" height="10" patternUnits="userSpaceOnUse">
                    <path d="M 10 0 L 0 0 0 10" fill="none" stroke="currentColor" stroke-width="0.5" />
                </pattern>
                <rect x="0" y="0" width="100" height="100" fill="url(#pattern-grid)" />
            </svg>
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ title }}</h1>
            <p v-if="subtitle" class="mt-4 sm:mt-6 max-w-2xl text-lg sm:text-xl text-blue-100">
                {{ subtitle }}
            </p>
            <div class="mt-8">
                <slot name="action" />
            </div>
        </div>
    </div>
</template>
