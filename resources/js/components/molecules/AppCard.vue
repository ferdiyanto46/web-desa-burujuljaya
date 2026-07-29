<script setup lang="ts">
interface Props {
    title?: string;
    description?: string;
    noPadding?: boolean;
}

withDefaults(defineProps<Props>(), {
    noPadding: false,
});
</script>

<template>
    <div class="overflow-hidden rounded-lg bg-white shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-800 dark:ring-white/10">
        <div v-if="title || $slots.header" class="border-b border-gray-200 px-4 py-5 sm:px-6 dark:border-gray-700">
            <slot name="header">
                <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-gray-100">
                    {{ title }}
                </h3>
                <p v-if="description" class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-400">
                    {{ description }}
                </p>
            </slot>
        </div>
        
        <div :class="[noPadding ? '' : 'px-4 py-5 sm:p-6']">
            <slot />
        </div>
        
        <div v-if="$slots.footer" class="bg-gray-50 px-4 py-4 sm:px-6 dark:bg-gray-900/50">
            <slot name="footer" />
        </div>
    </div>
</template>
