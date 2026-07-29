<script setup lang="ts">
interface Props {
    title: string;
    value: string | number;
    icon?: string;
    trend?: 'up' | 'down' | 'neutral';
    trendValue?: string;
}

defineProps<Props>();
</script>

<template>
    <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow-sm ring-1 ring-gray-900/5 sm:p-6 dark:bg-gray-800 dark:ring-white/10">
        <dt class="truncate text-sm font-medium text-gray-500 dark:text-gray-400">
            {{ title }}
        </dt>
        <dd class="mt-1 flex items-baseline justify-between md:block lg:flex">
            <div class="flex items-baseline text-2xl font-semibold text-gray-900 dark:text-white">
                {{ value }}
            </div>

            <div v-if="trend" :class="[
                trend === 'up' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 
                trend === 'down' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200',
                'inline-flex items-baseline rounded-full px-2.5 py-0.5 text-sm font-medium md:mt-2 lg:mt-0'
            ]">
                <svg v-if="trend === 'up'" class="-ml-1 mr-0.5 h-4 w-4 shrink-0 self-center text-green-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                </svg>
                <svg v-else-if="trend === 'down'" class="-ml-1 mr-0.5 h-4 w-4 shrink-0 self-center text-red-500" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" />
                </svg>
                <span v-if="trendValue">{{ trendValue }}</span>
            </div>
        </dd>
    </div>
</template>
