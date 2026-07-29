<script setup lang="ts">
import { Icons } from '@/constants/icons';
</script>

<template>
    <div class="flex gap-4">
        <a href="#" class="text-gray-400 hover:text-blue-500 transition-colors" aria-label="Facebook">
            <!-- Example: using standard feather icons or similar. For now, use fallback -->
            <component :is="Icons.UsersIcon" class="w-5 h-5" />
        </a>
        <a href="#" class="text-gray-400 hover:text-pink-500 transition-colors" aria-label="Instagram">
            <component :is="Icons.ImageIcon" class="w-5 h-5" />
        </a>
        <a href="#" class="text-gray-400 hover:text-red-500 transition-colors" aria-label="YouTube">
            <component :is="Icons.VideoIcon" class="w-5 h-5" />
        </a>
    </div>
</template>
