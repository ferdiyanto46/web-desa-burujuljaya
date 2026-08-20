<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Icons } from '@/constants/icons';
import { useLayoutStore } from '@/stores/layoutStore';
import Navigation from './Navigation.vue';
import TopBar from './TopBar.vue';

const layoutStore = useLayoutStore();
const page = usePage();

const village = computed(() => (page.props as any).village);
const logoUrl = computed(() => village.value?.logo_url || null);
const villageName = computed(() => village.value?.name || 'Desa Burujuljaya');
const regency = computed(() => village.value?.regency ? `Kab. ${village.value.regency}` : 'Kab. Tasikmalaya');
</script>

<template>
    <header class="sticky top-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-md border-b border-gray-200 dark:border-gray-800 shadow-sm transition-colors duration-300">
        <TopBar />
        
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 justify-between items-center">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <Link :href="route('home')" class="flex items-center gap-3 group">
                        <img 
                            v-if="logoUrl" 
                            class="h-10 w-auto max-w-[48px] object-contain transition-transform duration-200 group-hover:scale-105" 
                            :src="logoUrl" 
                            :alt="villageName" 
                        />
                        <div v-else class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                            {{ villageName.charAt(0) || 'D' }}
                        </div>
                        <div class="flex flex-col">
                            <span class="text-lg font-bold text-gray-900 dark:text-white leading-tight group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ villageName }}</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ regency }}</span>
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
                        class="p-2 text-gray-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 bg-gray-50 dark:bg-gray-800 rounded-full transition-colors"
                        aria-label="Toggle Dark Mode"
                    >
                        <component :is="layoutStore.isDark ? Icons.SunIcon : Icons.MoonIcon" class="h-5 w-5" aria-hidden="true" />
                    </button>
                    
                    <Link v-if="$page.props.auth?.user" :href="route('admin.dashboard')" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-colors">
                        Dashboard
                    </Link>
                    <Link v-else :href="route('login')" class="hidden md:inline-flex items-center justify-center px-4 py-2 border border-emerald-600 dark:border-emerald-500 rounded-lg text-sm font-medium text-emerald-600 dark:text-emerald-400 bg-transparent hover:bg-emerald-50 dark:hover:bg-emerald-950/30 transition-colors">
                        Login
                    </Link>

                    <!-- Mobile Menu Button -->
                    <div class="flex md:hidden">
                        <button 
                            @click="layoutStore.toggleMobileDrawer()" 
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:text-gray-300 dark:hover:text-white dark:hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-500 transition-colors"
                            aria-expanded="false"
                        >
                            <span class="sr-only">Buka menu utama</span>
                            <component :is="layoutStore.isMobileDrawerOpen ? Icons.XIcon : Icons.MenuIcon" class="block h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
