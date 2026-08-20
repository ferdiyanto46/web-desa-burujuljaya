<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Icons } from '@/constants/icons';

const page = usePage();
const village = computed(() => (page.props as any).village);
const phone = computed(() => village.value?.phone || '+62 812-3456-7890');
const email = computed(() => village.value?.email || 'desa.burujuljaya@gmail.com');
</script>

<template>
    <div class="bg-emerald-900 dark:bg-emerald-950 text-white py-2 text-xs sm:text-sm hidden md:block border-b border-emerald-800/40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a :href="`mailto:${email}`" class="flex items-center gap-2 hover:text-emerald-200 transition-colors">
                    <component :is="Icons.FileTextIcon" class="w-3.5 h-3.5" />
                    {{ email }}
                </a>
                <span class="flex items-center gap-2">
                    <component :is="Icons.PhoneIcon" class="w-3.5 h-3.5" />
                    {{ phone }}
                </span>
            </div>
            <div class="flex items-center gap-4 text-emerald-200 text-xs">
                <span>Jam Kerja: Sen - Jum, 08:00 - 15:00 WIB</span>
            </div>
        </div>
    </div>
</template>
