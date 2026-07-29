<script setup lang="ts">
import { Icons } from '@/constants/icons';
</script>

<template>
    <div class="bg-blue-900 text-white py-2 text-sm hidden md:block">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="mailto:desa.burujuljaya@gmail.com" class="flex items-center gap-2 hover:text-blue-200 transition-colors">
                    <component :is="Icons.FileTextIcon" class="w-4 h-4" /> <!-- Placeholder for Mail icon -->
                    desa.burujuljaya@gmail.com
                </a>
                <span class="flex items-center gap-2">
                    <component :is="Icons.MenuIcon" class="w-4 h-4" /> <!-- Placeholder for Phone icon -->
                    +62 812-3456-7890
                </span>
            </div>
            <div class="flex items-center gap-4">
                <span>Jam Kerja: Sen - Jum, 08:00 - 15:00 WIB</span>
            </div>
        </div>
    </div>
</template>
