<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    variant?: 'default' | 'primary' | 'success' | 'warning' | 'danger';
    size?: 'sm' | 'md' | 'lg';
    rounded?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'default',
    size: 'md',
    rounded: false,
});

const classes = computed(() => {
    const base = 'inline-flex items-center font-semibold transition-colors';
    
    const variants = {
        default: 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100',
        primary: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        success: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        warning: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        danger: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    };

    const sizes = {
        sm: 'px-2 py-0.5 text-xs',
        md: 'px-2.5 py-0.5 text-sm',
        lg: 'px-3 py-1 text-base',
    };

    return [
        base,
        variants[props.variant],
        sizes[props.size],
        props.rounded ? 'rounded-full' : 'rounded',
    ];
});
</script>

<template>
    <span :class="classes">
        <slot />
    </span>
</template>
