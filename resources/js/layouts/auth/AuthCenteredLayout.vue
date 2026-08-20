<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div class="min-h-dvh flex flex-col justify-center py-12 sm:px-6 lg:px-8 bg-gray-50 dark:bg-gray-950 relative overflow-hidden">
        <!-- Abstract Background Shapes for Elegance -->
        <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-1/2 -right-1/2 w-full h-full bg-blue-50 dark:bg-blue-900/20 rounded-full blur-3xl opacity-50"></div>
            <div class="absolute -bottom-1/2 -left-1/2 w-full h-full bg-indigo-50 dark:bg-indigo-900/20 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="sm:mx-auto sm:w-full sm:max-w-md relative z-10">
            <div class="flex justify-center">
                <Link :href="home()" class="flex flex-col items-center gap-3 group">
                    <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-gradient-to-br from-blue-600 to-indigo-600 shadow-lg group-hover:scale-105 transition-transform duration-300">
                        <AppLogoIcon class="size-10 fill-current text-white" />
                    </div>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white tracking-tight">Desa Burujuljaya</span>
                </Link>
            </div>
            
            <div class="mt-6 text-center space-y-2">
                <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white" v-if="title">
                    {{ title }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400" v-if="description">
                    {{ description }}
                </p>
            </div>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-[420px] relative z-10">
            <div class="bg-white dark:bg-gray-900 py-8 px-4 shadow-2xl sm:rounded-3xl sm:px-10 border border-gray-100 dark:border-gray-800">
                <slot />
            </div>
            
            <p class="mt-8 text-center text-xs text-gray-500 dark:text-gray-400">
                &copy; {{ new Date().getFullYear() }} Pemerintah Desa Burujuljaya. Hak Cipta Dilindungi.
            </p>
        </div>
    </div>
</template>
