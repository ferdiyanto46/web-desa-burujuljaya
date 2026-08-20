<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { home } from '@/routes';

const page = usePage();
const name = page.props.name;

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <div
        class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0"
    >
        <!-- Left Side: Brand Identity (Hidden on Mobile) -->
        <div
            class="relative hidden h-full flex-col bg-muted p-10 text-white lg:flex dark:border-r overflow-hidden"
        >
            <!-- Background Image -->
            <div class="absolute inset-0">
                <img src="/images/auth-bg.jpg" alt="Desa Burujuljaya" class="h-full w-full object-cover" />
                <!-- Premium Glassmorphism / Gradient Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-blue-950/90 via-blue-900/60 to-transparent"></div>
            </div>

            <!-- Header / Logo -->
            <Link
                :href="home()"
                class="relative z-20 flex items-center text-xl font-bold tracking-tight hover:text-blue-200 transition-colors"
            >
                <AppLogoIcon class="mr-3 size-10 fill-current text-white" />
                Sistem Informasi Desa<br>Burujuljaya
            </Link>

            <!-- Bottom Content (Welcome Message) -->
            <div class="relative z-20 mt-auto">
                <blockquote class="space-y-2">
                    <p class="text-lg font-medium leading-relaxed text-blue-50">
                        "Selamat datang di Sistem Informasi Administrasi Desa Burujuljaya. Wujudkan desa cerdas, transparan, dan melayani."
                    </p>
                    <footer class="text-sm text-blue-200 font-semibold">Pemerintah Desa Burujuljaya</footer>
                </blockquote>
            </div>
        </div>

        <!-- Right Side: Functional Auth Area -->
        <div class="lg:p-8 w-full">
            <div
                class="mx-auto flex w-full flex-col justify-center space-y-8 sm:w-[380px]"
            >
                <!-- Mobile Logo (Only visible on small screens) -->
                <div class="flex flex-col items-center lg:hidden mb-4">
                    <Link
                        :href="home()"
                        class="flex flex-col items-center gap-2 font-medium"
                    >
                        <div class="mb-2 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-600 shadow-md">
                            <AppLogoIcon class="size-8 fill-current text-white" />
                        </div>
                        <span class="text-lg font-bold text-gray-900 dark:text-white">Desa Burujuljaya</span>
                    </Link>
                </div>

                <div class="flex flex-col space-y-3 text-center lg:text-left">
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-gray-900 dark:text-white" v-if="title">
                        {{ title }}
                    </h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400" v-if="description">
                        {{ description }}
                    </p>
                </div>
                
                <!-- Main Form Slot -->
                <div class="bg-white dark:bg-gray-800/50 p-6 sm:p-8 rounded-2xl shadow-xl sm:shadow-sm border border-gray-100 dark:border-gray-700/50 lg:border-none lg:shadow-none lg:bg-transparent lg:p-0">
                    <slot />
                </div>
                
            </div>
        </div>
    </div>
</template>
