<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    collapsed?: boolean;
}

withDefaults(defineProps<Props>(), {
    collapsed: false,
});

const page = usePage();
const village = computed(() => (page.props as any).village);
const logoUrl = computed(() => village.value?.logo_url || null);
const villageName = computed(() => village.value?.name || 'SID Desa');
</script>

<template>
    <Link :href="route('admin.dashboard')" class="flex items-center gap-2.5 h-16 shrink-0 transition-all duration-300">
        <img 
            v-if="logoUrl" 
            :src="logoUrl" 
            :alt="villageName" 
            class="w-8 h-8 object-contain rounded-lg shrink-0" 
        />
        <div v-else class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-600 text-white font-bold shrink-0">
            {{ villageName.charAt(0) || 'S' }}
        </div>
        <span v-if="!collapsed" class="text-base font-bold tracking-tight truncate dark:text-white transition-opacity duration-300">
            {{ villageName }}
        </span>
    </Link>
</template>
