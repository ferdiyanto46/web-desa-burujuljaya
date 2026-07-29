<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface Props {
    collapsed?: boolean;
}

withDefaults(defineProps<Props>(), {
    collapsed: false,
});
</script>

<template>
    <Link :href="route('admin.dashboard')" class="flex items-center gap-2 px-4 h-16 shrink-0 transition-all duration-300">
        <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-blue-600 text-white font-bold shrink-0">
            S
        </div>
        <span v-if="!collapsed" class="text-xl font-bold tracking-tight truncate dark:text-white transition-opacity duration-300">
            SID Admin
        </span>
    </Link>
</template>
