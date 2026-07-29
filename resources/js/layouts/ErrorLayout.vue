<script setup lang="ts">
interface Props {
    status: number;
}

defineProps<Props>();
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-9xl font-black text-gray-200 dark:text-gray-800">{{ status }}</h1>
            
            <div class="mt-8">
                <slot>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-white">
                        Oops! Something went wrong.
                    </h2>
                    <p class="mt-2 text-base text-gray-500 dark:text-gray-400">
                        The page you are looking for might be unavailable or does not exist.
                    </p>
                </slot>
            </div>
            
            <div class="mt-10 flex justify-center gap-x-6">
                <slot name="actions">
                    <a href="/" class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                        Go back home
                    </a>
                </slot>
            </div>
        </div>
    </div>
</template>
