<script setup lang="ts">
interface Props {
    title?: string;
}

withDefaults(defineProps<Props>(), {
    title: 'Filter',
});
</script>

<template>
    <div class="rounded-lg bg-gray-50 p-4 shadow-sm ring-1 ring-gray-900/5 dark:bg-gray-800/50 dark:ring-white/10">
        <h4 v-if="title" class="mb-4 text-sm font-medium text-gray-900 dark:text-gray-100">
            {{ title }}
        </h4>
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            <slot />
        </div>
        <div v-if="$slots.actions" class="mt-4 flex items-center justify-end space-x-3 border-t border-gray-200 pt-4 dark:border-gray-700">
            <slot name="actions" />
        </div>
    </div>
</template>
