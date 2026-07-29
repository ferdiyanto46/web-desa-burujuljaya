<script setup lang="ts">
defineProps<{
    categories: Array<{
        value: string;
        label: string;
    }>;
    modelValue?: string | null;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
    <div class="flex flex-wrap gap-2">
        <button 
            type="button"
            @click="emit('update:modelValue', '')"
            :class="[
                !modelValue 
                    ? 'bg-blue-600 text-white border-blue-600' 
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400',
                'px-4 py-2 rounded-full border text-sm font-medium transition-colors'
            ]"
        >
            Semua
        </button>
        <button 
            type="button"
            v-for="category in categories" 
            :key="category.value"
            @click="emit('update:modelValue', category.value)"
            :class="[
                modelValue === category.value 
                    ? 'bg-blue-600 text-white border-blue-600' 
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700 hover:border-blue-500 hover:text-blue-600 dark:hover:text-blue-400',
                'px-4 py-2 rounded-full border text-sm font-medium transition-colors'
            ]"
        >
            {{ category.label }}
        </button>
    </div>
</template>
