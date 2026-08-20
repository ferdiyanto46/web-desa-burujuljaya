<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Icons } from '@/constants/icons';
import FooterLinks from './FooterLinks.vue';
import SocialLinks from './SocialLinks.vue';

const page = usePage();
const village = computed(() => (page.props as any).village);
const logoUrl = computed(() => village.value?.logo_url || null);
const villageName = computed(() => village.value?.name || 'Desa Burujuljaya');
const regency = computed(() => village.value?.regency ? `Kabupaten ${village.value.regency}` : 'Kabupaten Tasikmalaya');
const address = computed(() => village.value?.address || 'Jl. Raya Burujuljaya No. 123, Kec. Parungponteng, Kab. Tasikmalaya, Jawa Barat 46181');
const phone = computed(() => village.value?.phone || '+62 858-421-14748');
const email = computed(() => village.value?.email || 'desa.burujuljaya@gmail.com');
</script>

<template>
    <footer class="bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 transition-colors mt-auto">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="xl:grid xl:grid-cols-3 xl:gap-12">
                <!-- Brand & Address -->
                <div class="xl:col-span-1 flex flex-col gap-6">
                    <div class="flex items-center gap-3">
                        <img 
                            v-if="logoUrl" 
                            class="h-12 w-auto max-w-[56px] object-contain transition-all" 
                            :src="logoUrl" 
                            :alt="villageName" 
                        />
                        <div v-else class="w-12 h-12 rounded-xl bg-emerald-600 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                            {{ villageName.charAt(0) || 'D' }}
                        </div>
                        <div>
                            <span class="block text-xl font-bold text-gray-900 dark:text-white leading-tight">{{ villageName }}</span>
                            <span class="block text-sm text-gray-500 dark:text-gray-400">{{ regency }}</span>
                        </div>
                    </div>
                    
                    <div class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed max-w-sm">
                        <p class="mb-2 whitespace-pre-line">
                            {{ address }}
                        </p>
                        <p v-if="phone" class="flex items-center gap-2 mt-4 text-gray-600 dark:text-gray-300">
                            <component :is="Icons.PhoneIcon" class="w-4 h-4 text-emerald-600" /> 
                            {{ phone }}
                        </p>
                        <p v-if="email" class="flex items-center gap-2 mt-2 text-gray-600 dark:text-gray-300">
                            <component :is="Icons.FileTextIcon" class="w-4 h-4 text-emerald-600" /> 
                            {{ email }}
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
                    &copy; {{ new Date().getFullYear() }} Pemerintah {{ villageName }}. Hak cipta dilindungi undang-undang.
                </p>
                <div class="text-sm text-gray-400">
                    Sistem Informasi Desa (SID)
                </div>
            </div>
        </div>
    </footer>
</template>
