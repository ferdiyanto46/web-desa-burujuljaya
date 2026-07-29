<script setup lang="ts">
import { Icons } from '@/constants/icons';
import FooterLinks from './FooterLinks.vue';
import SocialLinks from './SocialLinks.vue';
</script>

<template>
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 transition-colors mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="xl:grid xl:grid-cols-3 xl:gap-12">
                <!-- Brand & Address -->
                <div class="xl:col-span-1 flex flex-col gap-6">
                    <div class="flex items-center gap-3">
                        <img class="h-10 w-auto grayscale dark:invert opacity-70 hover:grayscale-0 hover:opacity-100 transition-all" src="/images/logo-majalengka.png" alt="Logo" onerror="this.src='data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI0MCIgaGVpZ2h0PSI0MCIgdmlld0JveD0iMCAwIDQwIDQwIj48cmVjdCB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIGZpbGw9IiNjY2MiLz48L3N2Zz4='" />
                        <div>
                            <span class="block text-xl font-bold text-gray-900 dark:text-white leading-tight">Desa Burujuljaya</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">Kabupaten Majalengka</span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-sm">
                        <p class="mb-2">
                            Jl. Raya Burujuljaya No. 123, Kec. Parungponteng,<br>
                            Kab. Majalengka, Jawa Barat 45465
                        </p>
                        <p class="flex items-center gap-2 mt-4">
                            <component :is="Icons.MenuIcon" class="w-4 h-4" /> 
                            +62 812-3456-7890
                        </p>
                        <p class="flex items-center gap-2 mt-2">
                            <component :is="Icons.FileTextIcon" class="w-4 h-4" /> 
                            desa.burujuljaya@gmail.com
                        </p>
                    </div>
                    
                    <SocialLinks />
                </div>
                
                <!-- Links -->
                <div class="mt-12 grid grid-cols-1 gap-8 xl:mt-0 xl:col-span-2">
                    <FooterLinks />
                </div>
            </div>
            
            <div class="mt-12 border-t border-gray-200 dark:border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-base text-gray-400 xl:text-center">
                    &copy; {{ new Date().getFullYear() }} Pemerintah Desa Burujuljaya. Hak cipta dilindungi undang-undang.
                </p>
                <div class="text-sm text-gray-400">
                    Sistem Informasi Desa (SID)
                </div>
            </div>
        </div>
    </footer>
</template>
