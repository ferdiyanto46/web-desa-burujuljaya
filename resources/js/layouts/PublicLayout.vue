<script setup lang="ts">
import { Toaster } from 'vue-sonner';
import Footer from '@/components/public/Footer.vue';
import Header from '@/components/public/Header.vue';
import MobileNavigation from '@/components/public/MobileNavigation.vue';
import { useLayoutStore } from '@/stores/layoutStore';

const layoutStore = useLayoutStore();
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300 flex flex-col">
        <!-- Header -->
        <Header />
        
        <!-- Mobile Navigation Drawer -->
        <div v-if="layoutStore.isMobileDrawerOpen" class="md:hidden border-b border-gray-200 dark:border-gray-700 shadow-sm relative z-30">
            <MobileNavigation />
        </div>

        <!-- Main Content -->
        <main class="flex-grow flex flex-col">
            <!-- Breadcrumbs / Page Banner usually placed here by pages -->
            <slot name="banner" />
            
            <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8 w-full flex-grow">
                <div v-if="$slots.breadcrumb" class="mb-6">
                    <slot name="breadcrumb" />
                </div>
                
                <slot />
            </div>
        </main>

        <!-- Footer -->
        <Footer />

        <!-- Toast Notifications -->
        <Toaster position="top-right" rich-colors />
    </div>
</template>
