<script setup lang="ts">
interface Props {
    label: string;
    collapsed?: boolean;
}

withDefaults(defineProps<Props>(), {
    collapsed: false,
});
</script>

<template>
    <div 
        class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider dark:text-gray-400 transition-opacity duration-300"
        :class="{ 'opacity-0 h-0 p-0 overflow-hidden': collapsed }"
    >
        {{ label }}
    </div>
</template>
