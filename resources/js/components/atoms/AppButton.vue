<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'primary' | 'secondary' | 'danger' | 'success' | 'warning' | 'outline' | 'ghost';
    size?: 'sm' | 'md' | 'lg';
    type?: 'button' | 'submit' | 'reset';
    disabled?: boolean;
    loading?: boolean;
    block?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    type: 'button',
    disabled: false,
    loading: false,
    block: false,
});

const baseClasses = 'inline-flex items-center justify-center font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed rounded-md';

const variantClasses = computed(() => {
    switch (props.variant) {
        case 'primary': return 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500';
        case 'secondary': return 'bg-gray-200 text-gray-900 hover:bg-gray-300 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-100 dark:hover:bg-gray-600';
        case 'danger': return 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500';
        case 'success': return 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500';
        case 'warning': return 'bg-yellow-500 text-white hover:bg-yellow-600 focus:ring-yellow-500';
        case 'outline': return 'border border-gray-300 bg-transparent text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-800';
        case 'ghost': return 'bg-transparent text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800';
        default: return 'bg-blue-600 text-white hover:bg-blue-700';
    }
});

const sizeClasses = computed(() => {
    switch (props.size) {
        case 'sm': return 'text-xs px-3 py-1.5';
        case 'md': return 'text-sm px-4 py-2';
        case 'lg': return 'text-base px-6 py-3';
        default: return 'text-sm px-4 py-2';
    }
});

const classes = computed(() => [
    baseClasses,
    variantClasses.value,
    sizeClasses.value,
    props.block ? 'w-full' : '',
]);
</script>

<template>
    <button
        :type="type"
        :class="classes"
        :disabled="disabled || loading"
    >
        <span v-if="loading" class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></span>
        <slot />
    </button>
</template>
