<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    selectedCount: number;
}

const props = defineProps<Props>();

const emit = defineEmits<{
    (e: 'clear'): void;
}>();

const hasSelection = computed(() => props.selectedCount > 0);
</script>

<template>
    <div 
        v-if="hasSelection" 
        class="fixed bottom-4 left-1/2 z-50 -translate-x-1/2 flex items-center gap-4 rounded-lg bg-gray-900 px-6 py-3 text-white shadow-xl dark:bg-gray-800"
    >
        <div class="flex items-center gap-2">
            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white">
                {{ selectedCount }}
            </span>
            <span class="text-sm font-medium">item terpilih</span>
        </div>
        
        <div class="h-4 w-px bg-gray-600"></div>
        
        <div class="flex items-center gap-2">
            <slot name="actions" />
        </div>
        
        <div class="h-4 w-px bg-gray-600"></div>
        
        <button 
            type="button"
            @click="emit('clear')" 
            class="text-sm font-medium text-gray-300 hover:text-white transition-colors"
        >
            Batal
        </button>
    </div>
</template>
