<script setup lang="ts">
interface Props {
    size?: 'sm' | 'md' | 'lg';
    color?: 'current' | 'primary' | 'white';
}

withDefaults(defineProps<Props>(), {
    size: 'md',
    color: 'current',
});

const sizeClasses = {
    sm: 'h-4 w-4',
    md: 'h-8 w-8',
    lg: 'h-12 w-12',
};

const colorClasses = {
    current: 'text-current',
    primary: 'text-blue-600 dark:text-blue-400',
    white: 'text-white',
};
</script>

<template>
    <div class="flex items-center justify-center" role="status">
        <svg :class="['animate-spin', sizeClasses[size], colorClasses[color]]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <span class="sr-only">Loading...</span>
    </div>
</template>
