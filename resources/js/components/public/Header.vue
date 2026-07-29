<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Icons } from '@/constants/icons';
import { useLayoutStore } from '@/stores/layoutStore';
import Navigation from './Navigation.vue';
import TopBar from './TopBar.vue';

const layoutStore = useLayoutStore();
</script>

<template>
    <header class="sticky top-0 z-40 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md border-b border-gray-200 dark:border-gray-700 shadow-sm transition-colors duration-300">
        <TopBar />
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <Link :href="route('home')" class="flex items-center gap-3">
                        <img class="h-10 w-auto" src="/images/logo-majalengka.png" alt="Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIGZpbGw9IiNjY2MiLz48L3N2Zz4='" />
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-gray-900 dark:text-white leading-tight">Desa Burujuljaya</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Kab. Majalengka</span>
                        </div>
                    </Link>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex flex-1 justify-center">
                    <Navigation />
                </nav>
                
                <!-- Actions -->
                <div class="flex items-center gap-4">
                    <button 
                        @click="layoutStore.toggleDarkMode()" 
                        class="p-2 text-gray-500 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400 bg-gray-50 dark:bg-gray-700 rounded-full transition-colors"
                        aria-label="Toggle Dark Mode"
                    >
                        <component :is="layoutStore.isDark ? Icons.SunIcon : Icons.MoonIcon" class="h-5 w-5" aria-hidden="true" />
                    </button>
                    
                    <Link v-if="$page.props.auth?.user" :href="route('admin.dashboard')" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                        Dashboard
                    </Link>
                    <Link v-else :href="route('login')" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-blue-600 dark:border-blue-500 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 bg-transparent hover:bg-blue-50 dark:hover:bg-blue-900/30 transition-colors">
                        Login
                    </Link>

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden">
                        <button 
                            @click="layoutStore.toggleMobileDrawer()" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500 transition-colors"
                            aria-expanded="false"
                        >
                            <span class="sr-only">Buka menu utama</span>
                            <component :is="layoutStore.isMobileDrawerOpen ? Icons.XIcon : Icons.MenuIcon" class="block h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation Drawer handled in Layout -->
    </header>
</template>
