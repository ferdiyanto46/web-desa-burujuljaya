<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    title?: string;
    subtitle?: string;
    backgroundImage?: string;
    pageKey?: string; // e.g. 'profile', 'articles', 'gallery', 'umkm', 'documents', 'complaints', 'tourism', 'services', 'faq', 'contact'
}>();

const page = usePage();

const bannerConfig = computed(() => {
    if (!props.pageKey) return null;
    const allBanners = (page.props as any).page_banners || {};
    return allBanners[props.pageKey] || null;
});

const resolvedTitle = computed(() => {
    return props.title || bannerConfig.value?.title || 'Informasi Desa';
});

const resolvedSubtitle = computed(() => {
    if (props.subtitle !== undefined) return props.subtitle;
    return bannerConfig.value?.subtitle || '';
});

const resolvedImage = computed(() => {
    if (props.backgroundImage) return props.backgroundImage;
    const customImage = bannerConfig.value?.image_url || bannerConfig.value?.image_path;
    if (customImage) {
        return customImage.startsWith('http') ? customImage : (customImage.startsWith('/') ? customImage : `/storage/${customImage}`);
    }
    return null;
});
</script>

<template>
    <div class="relative bg-blue-900 dark:bg-slate-950 overflow-hidden transition-colors duration-300" :class="{'py-16 sm:py-24': !resolvedImage, 'py-24 sm:py-32': resolvedImage}">
        <!-- Background Image with Overlay -->
        <div v-if="resolvedImage" class="absolute inset-0">
            <img :src="resolvedImage" :alt="resolvedTitle" class="w-full h-full object-cover" />
            <div class="absolute inset-0 bg-blue-950/75 dark:bg-slate-950/80 mix-blend-multiply" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-blue-950 via-blue-950/50 to-transparent dark:from-slate-950 opacity-90" aria-hidden="true"></div>
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

        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col items-center text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">{{ resolvedTitle }}</h1>
            <p v-if="resolvedSubtitle" class="mt-4 sm:mt-6 max-w-2xl text-lg sm:text-xl text-blue-100 dark:text-gray-200">
                {{ resolvedSubtitle }}
            </p>
            <div v-if="$slots.action" class="mt-8">
                <slot name="action" />
            </div>
        </div>
    </div>
</template>
