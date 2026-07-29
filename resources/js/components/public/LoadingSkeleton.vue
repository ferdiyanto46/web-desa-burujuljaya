<template>
    <div class="animate-pulse">
        <slot>
            <!-- Default skeleton if no slot provided -->
            <div class="bg-gray-200 dark:bg-gray-700 rounded-2xl h-48 w-full mb-4"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4 mb-3"></div>
            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-1/2"></div>
        </slot>
    </div>
</template>
